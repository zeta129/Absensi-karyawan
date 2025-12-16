from fastapi import FastAPI, UploadFile, File, HTTPException
from pydantic import BaseModel
import os
import base64
import io
from typing import Optional

# Optional imports: if the runtime does not have these packages installed
# we capture that and return a helpful HTTP error instead of letting the
# app crash with a ModuleNotFoundError. This also quiets some editor
# "missing imports" messages from static checkers when run under a
# normal development environment.
try:
    import face_recognition  # type: ignore
except Exception:
    face_recognition = None  # type: ignore

try:
    import numpy as np  # type: ignore
except Exception:
    np = None  # type: ignore

try:
    from PIL import Image  # type: ignore
except Exception:
    Image = None  # type: ignore

app = FastAPI(title="Face Recognition Service")

FACES_DIR = os.path.join(os.path.dirname(__file__), 'faces')
THRESHOLD = 0.55  # distance threshold (lower is stricter)

class RecognizeRequest(BaseModel):
    image_base64: str

@app.post('/recognize')
async def recognize(payload: RecognizeRequest):
    """Accepts a base64 image and compares against reference faces in `faces/`.
    Expects reference images named with user id, e.g. `123.jpg` or `user_123.jpg`.
    Returns { matched: true/false, user_id: <id> or null, distance: <float> }
    """
    # ensure required libraries are available
    if face_recognition is None or np is None or Image is None:
        raise HTTPException(status_code=500, detail=(
            "face_recognition service dependencies are not installed. "
            "Install requirements listed in face_recognition_service/requirements.txt "
            "or run the dockerized service."
        ))

    try:
        header, data = payload.image_base64.split(',', 1) if ',' in payload.image_base64 else (None, payload.image_base64)
        image_data = base64.b64decode(data)
    except Exception as e:
        raise HTTPException(status_code=400, detail=f"Invalid base64 image: {e}")

    img = Image.open(io.BytesIO(image_data)).convert('RGB')
    img_np = np.array(img)

    # Get embeddings for incoming image
    faces = face_recognition.face_locations(img_np)
    if not faces:
        return {"matched": False, "reason": "no_face_detected"}

    encodings = face_recognition.face_encodings(img_np, faces)
    if not encodings:
        return {"matched": False, "reason": "no_encodings"}

    probe_encoding = encodings[0]

    # iterate reference faces
    best_match = None
    best_distance = 999
    if not os.path.exists(FACES_DIR):
        return {"matched": False, "reason": "no_reference_faces"}

    for fname in os.listdir(FACES_DIR):
        fpath = os.path.join(FACES_DIR, fname)
        try:
            ref_img = face_recognition.load_image_file(fpath)
            ref_encs = face_recognition.face_encodings(ref_img)
            if not ref_encs:
                continue
            ref_encoding = ref_encs[0]
            dist = np.linalg.norm(probe_encoding - ref_encoding)
            if dist < best_distance:
                best_distance = float(dist)
                # extract user id from filename (before first dot)
                user_id = os.path.splitext(fname)[0]
                best_match = user_id
        except Exception:
            continue

    if best_match is None:
        return {"matched": False, "reason": "no_reference_faces"}

    matched = best_distance <= THRESHOLD
    return {"matched": matched, "user_id": best_match if matched else None, "distance": best_distance}


class EnrollRequest(BaseModel):
    image_base64: str
    user_id: str


@app.post('/enroll')
async def enroll(payload: EnrollRequest):
    """Enroll a reference face for `user_id`. Saves file to faces/{user_id}.jpg after verifying a face is present.
    Returns { enrolled: true/false, reason?: string }
    """
    try:
        header, data = payload.image_base64.split(',', 1) if ',' in payload.image_base64 else (None, payload.image_base64)
        image_data = base64.b64decode(data)
    except Exception as e:
        raise HTTPException(status_code=400, detail=f"Invalid base64 image: {e}")

    img = Image.open(io.BytesIO(image_data)).convert('RGB')
    img_np = np.array(img)

    faces = face_recognition.face_locations(img_np)
    if not faces:
        return {"enrolled": False, "reason": "no_face_detected"}

    # ensure faces directory exists
    os.makedirs(FACES_DIR, exist_ok=True)

    # Save image to faces/{user_id}.jpg (overwrite if exists)
    out_path = os.path.join(FACES_DIR, f"{payload.user_id}.jpg")
    try:
        img.save(out_path, format='JPEG')
    except Exception as e:
        raise HTTPException(status_code=500, detail=f"Failed to save image: {e}")

    return {"enrolled": True, "user_id": payload.user_id}
