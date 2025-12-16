FROM continuumio/miniconda3:latest

WORKDIR /app

# Copy requirements and app
COPY requirements.txt /app/requirements.txt
COPY app.py /app/app.py
RUN mkdir -p /app/faces

# Create conda environment and install prebuilt packages from conda-forge
RUN conda install -y -c conda-forge \
    python=3.10 \
    dlib \
    face_recognition \
    opencv \
    numpy \
    pillow && \
    pip install --no-cache-dir fastapi "uvicorn[standard]" python-multipart

# Copy rest of service (if you add other files later)
COPY . /app

EXPOSE 8001

CMD ["uvicorn", "app:app", "--host", "0.0.0.0", "--port", "8001"]
