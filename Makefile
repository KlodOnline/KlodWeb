build:
	docker build -t klodweb .
run:
	docker run -p 1443:443 -v "$(PWD):/var/klodweb" -it --name klodweb klodweb
