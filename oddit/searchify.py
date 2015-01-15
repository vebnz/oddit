from indextank.client import ApiClient

api = ApiClient('http://:twdfy1QVjimypE@61rg.api.searchify.com')
search = api.get_index('jobs')

print search('title:WebGL')
