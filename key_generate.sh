openssl genrsa -out storage/jwtRS256.key 1024

openssl rsa -in storage/jwtRS256.key -pubout > storage/jwtRS256.key.pub


