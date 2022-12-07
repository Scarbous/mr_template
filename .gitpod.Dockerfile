# You can find the new timestamped tags here: https://hub.docker.com/r/gitpod/workspace-full/tags
FROM gitpod/workspace-full:2022-12-02-22-15-49

ENV SHELL=/usr/bin/zsh

# Change your version here
RUN sudo update-alternatives --set php $(which php7.4)
