import re, requests
url = 'https://app.pixverse.ai/video/411726554513286'
headers = {'User-Agent': 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36'}
resp = requests.get(url, headers=headers, timeout=30)
m = re.search(r'contentUrl:"([^"]+)"', resp.text)
if m:
    print('Found:', m.group(1))
else:
    print('Not found with regex')
    m2 = re.search(r'https://media\.pixverse\.ai/pixverse%2Fmp4%2F[^"\\]+\.mp4', resp.text)
    if m2:
        print('Alt found:', m2.group(0))
    else:
        print('Still not found')
