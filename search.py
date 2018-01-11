import wechatsogou
import sys

searchName = sys.argv[1]

ws_api = wechatsogou.WechatSogouAPI(timeout=0.1)

print(ws_api.search_article(searchName))



