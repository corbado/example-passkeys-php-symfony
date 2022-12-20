FROM python:3

#WORKDIR /usr/src/app

RUN apt-get update -y && apt-get upgrade -y

COPY requirements.txt /usr/src/app/requirements.txt
#RUN cd /usr/src/app && pip install --no-cache-dir -r requirements.txt

COPY . /usr/src/app

ENTRYPOINT [ "/usr/src/app/projectconfigurator/docker/startup.sh" ]