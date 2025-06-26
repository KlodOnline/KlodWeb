build:
	docker build -t klodweb .
	docker run -p 1443:443 -v ".:/var/klodweb" -it --rm --name klodweb klodweb
