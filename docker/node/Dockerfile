ARG NODE_VERSION
ARG UID=1000
ARG GID=1000

FROM node:${NODE_VERSION}-alpine

ARG UID
ARG GID

RUN apk --no-cache add shadow && usermod --uid "$UID" node ; groupmod --gid "$GID" node

USER node