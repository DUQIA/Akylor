import json
with open('world.json', 'r', encoding='utf-8') as f:
    file_content = f.read()
    json_content = json.loads(file_content)
    data = json_content['features']

# 中英对照
# for  i in range(len(data)):
#     with open('test.txt', 'a', encoding='utf-8') as a:
#         a.writelines("'" + data[i]['properties']['name'] + "' : ''," '\n')

# 英文
for i in range(len(data)):
    with open('en.txt', 'a', encoding='utf-8') as y:
        y.writelines("'" + data[i]['properties']['name'] + "' : '" + data[i]['properties']['name'] + "'," '\n')