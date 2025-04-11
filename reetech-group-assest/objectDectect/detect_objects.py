import cv2
from ultralytics import YOLO
import yaml
import json
import os
from collections import defaultdict

# Load config
with open("config.yaml", "r") as f:
    config = yaml.safe_load(f)

# Initialize YOLO model
model = YOLO(config["yolo"]["model"])

# Map object names to YOLO class IDs
class_map = {
    "person": 0,
    "dog": 16,
    "sofa": 57,
}

# Process all videos in the input directory
results = {}

for video_file in os.listdir(config["video"]["input_dir"]):
    if video_file.endswith((".mp4", ".avi", ".mov")):  # Add other formats if needed
        video_path = os.path.join(config["video"]["input_dir"], video_file)
        cap = cv2.VideoCapture(video_path)
        fps = cap.get(cv2.CAP_PROP_FPS)
        
        detections = defaultdict(list)
        frame_number = 0

        while cap.isOpened():
            ret, frame = cap.read()
            if not ret:
                break

            # Detect objects
            outputs = model(frame, conf=config["yolo"]["confidence_threshold"])
            
            # Track objects in this frame
            detected_labels = set()
            for r in outputs:
                for box in r.boxes:
                    class_id = int(box.cls)
                    for obj, obj_id in class_map.items():
                        if class_id == obj_id:
                            detected_labels.add(obj)

            # Check for "dog with person"
            if "dog" in detected_labels and "person" in detected_labels:
                detected_labels.add("dog with person")

            # Record timestamps
            current_time = frame_number / fps
            for obj in detected_labels:
                detections[obj].append(current_time)

            frame_number += 1

        cap.release()
        results[video_file] = detections

# Save results to JSON
with open(config["video"]["output_json"], "w") as f:
    json.dump(results, f, indent=4)

print(f"Results saved to {config['video']['output_json']}")