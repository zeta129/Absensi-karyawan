@echo off
REM Activate virtualenv first, then run uvicorn
if exist .venv\Scripts\activate.bat (
  call .venv\Scripts\activate.bat
)
@echo off
echo Starting face_recognition service using Docker Compose...
docker-compose up -d --build face-recognition
if %ERRORLEVEL% NEQ 0 (
  echo Docker Compose failed, attempting local fallback...
  echo Please ensure you have conda or the required build tools to install dlib.
  pip install -r requirements.txt
  uvicorn app:app --host 0.0.0.0 --port 8001
)
pause
