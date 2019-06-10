FROM java:8-jre

ENV GIT_BUCKET_VERSION=4.31.2
ENV PREFIX=git

ADD https://github.com/gitbucket/gitbucket/releases/download/$GIT_BUCKET_VERSION/gitbucket.war /opt/gitbucket.war

RUN ln -s /gitbucket /root/.gitbucket

VOLUME /gitbucket


# Port for web page
EXPOSE 8080
# Port for SSH access to git repository (Optional)
EXPOSE 29418

ENV MAX_FILE_SIZE=3145728

CMD ["sh", "-c", "java -jar /opt/gitbucket.war --max_file_size=$MAX_FILE_SIZE --prefix=/$PREFIX"]
