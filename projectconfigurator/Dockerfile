FROM python:3.8

RUN apt-get update -y && apt-get upgrade -y

RUN pip install --upgrade pip

COPY . /usr/src/app

RUN cd /usr/src/app && pip install --no-cache-dir -r requirements.txt

ENTRYPOINT [ "/usr/src/app/projectconfigurator/docker/startup.sh" ]