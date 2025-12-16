Face Recognition Service

This small FastAPI service compares incoming base64 face images with reference face images stored in the `faces/` folder.

Quick start (Windows):

1. Create a venv and install dependencies:

```powershell
python -m venv .venv
.\.venv\Scripts\Activate.ps1
pip install -r requirements.txt
```

2. Create `faces/` folder and add reference images named by user id, e.g. `42.jpg`.

3. Run the service:

```powershell
uvicorn app:app --host 0.0.0.0 --port 8001
```

API:
- POST `/recognize` JSON payload: `{ "image_base64": "data:image/jpeg;base64,..." }`
- Response: `{ matched: bool, user_id: string|null, distance: float }

Notes:
- This service uses `face_recognition` (dlib). Installing may require build tools and C++ toolchain on Windows.
- Tweak `THRESHOLD` in `app.py` to make matching stricter/looser.
