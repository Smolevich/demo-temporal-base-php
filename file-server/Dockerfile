FROM --platform=linux/arm64 python:3.12

RUN pip install flask

WORKDIR /app

COPY server.py /app/server.py
COPY data /app/data

EXPOSE 5000

CMD ["python", "server.py"]
