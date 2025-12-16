# Face Recognition Service (Docker)

This document shows how to run the FastAPI face recognition microservice inside Docker. Using Docker avoids the need to build `dlib` on Windows and provides a consistent environment.

Prerequisites
- Docker Desktop installed and running on your Windows machine.

Build and start the service

From the project root (where `docker-compose.yml` lives):

```powershell
docker-compose build --no-cache face-recognition
docker-compose up -d face-recognition
```

The service will be available at `http://localhost:8001` and the endpoints are:
- `POST /recognize` — recognize face images (same payload as existing service)
- `POST /enroll` — enroll a face for a user

Persisted reference faces
- The directory `face_recognition_service/faces` is mounted into the container at `/app/faces`. Enrolled face images will be stored there on the host.

Stopping the service

```powershell
docker-compose down
```

Notes
- If you prefer a local Python environment, use the Conda instructions in the main README. Docker is chosen here because it reliably provides prebuilt `dlib` and `face_recognition` packages across platforms.
