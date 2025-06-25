build:
	docker build -t klodweb .
	docker run -p 1443:443 -it --rm --name klodweb klodweb