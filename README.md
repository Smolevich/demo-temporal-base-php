# demo-temporal-base-php
demo for work with Temporal with rr+php cli workers


This project demonstrates working with Temporal using RoadRunner (RR) and PHP CLI workers. 

## Overview
The project showcases various examples of Temporal workflow implementations using PHP, including:
- Basic workflow patterns and best practices
- Integration with RoadRunner for PHP worker management
- Activity implementations and orchestration
- Error handling and retry policies
- Workflow state management
- Signal and query handling

## Key Components
- Temporal Server - Orchestrates workflow execution
- RoadRunner - Manages PHP worker processes
- PHP Workers - Execute workflow and activity code
- Example Workflows - Demonstrate different Temporal features and patterns

## Getting Started
See the examples directory for various workflow implementations and usage patterns.
Documentation includes setup instructions and explanations of each example.


1. **NYC Taxi Data** (about 2GB per month):
```bash
# Download Yellow Taxi Trip Records for January 2023
wget https://d37ci6vzurychx.cloudfront.net/trip-data/yellow_tripdata_2023-01.parquet

# Install required Python packages for conversion
pip3 install pandas pyarrow

# Convert Parquet to CSV format
python3 -c "import pandas as pd; pd.read_parquet('yellow_tripdata_2023-01.parquet').to_csv('largefile.csv', index=False)"
```


2. **GitHub Archive** (about 3GB per month):
```bash
# Download GitHub events archive for January 1st, 2024 (all 24 hours)
wget https://data.gharchive.org/2024-01-01-{0..23}.json.gz

# Decompress and merge all files into one CSV
gunzip -c 2024-01-01-*.json.gz > largefile.csv
```


3. **Stack Overflow posts dump** (about 2.5GB):
```bash
# Download Stack Overflow posts dump
wget https://archive.org/download/stackexchange/stackoverflow.com-Posts.7z

# Extract the archive (requires p7zip)
7z x stackoverflow.com-Posts.7z
mv Posts.xml largefile.csv
```

