build:
	docker build -t klodweb .
run:
	docker run -d -p 1443:443 -v "$(PWD):/var/klodweb" --name klodweb klodweb
