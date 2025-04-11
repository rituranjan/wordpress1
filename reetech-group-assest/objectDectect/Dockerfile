# Use Python 3.9 as base image
FROM python:3.9-slim

# Install system dependencies (OpenCV needs libgl1)
RUN apt-get update && apt-get install -y \
    libgl1 \
    && rm -rf /var/lib/apt/lists/*

# Set working directory
WORKDIR /app

# Copy requirements and install Python dependencies
COPY requirements.txt .
RUN pip install --no-cache-dir -r requirements.txt

# Copy the rest of the project files
COPY . .

# Command to run the object detection script
CMD ["python", "detect_objects.py"]