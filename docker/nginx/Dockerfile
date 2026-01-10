FROM nginx:1.17.8-alpine

# USUWAMY DOMYŚLNY CONFIG
RUN rm /etc/nginx/conf.d/default.conf

# KOPIUJEMY NASZ JAKO GŁÓWNY
COPY ./docker/nginx/nginx.conf /etc/nginx/nginx.conf

COPY . /app/
