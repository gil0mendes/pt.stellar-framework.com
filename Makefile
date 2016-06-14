all:
	rm -f db.json
	hexo generate
	hexo deploy
