from ftplib import FTP
import json

with open("scripts/files.json", 'r') as json_file:
    files_to_update = json.load(json_file)

ftp = FTP('ftpupload.net', 'epiz_31123825', '0nwNXAwGlQ0KzjV')


print("TRANSFERRING ALL FILES TO WEBSERVER")


ftp.cwd('~/htdocs')
for file in files_to_update['root']:
    with open(file, 'rb') as infile:
        print(f"Transferring {file}")
        stor_command = f"STOR {file}"
        response = ftp.storbinary(stor_command, fp=infile)
        print(response)

print(ftp.cwd('~/htdocs/js'))
for file in files_to_update['js']:
    with open(f'js/{file}', 'rb') as infile:
        print(f"Transferring {file}")
        stor_command = f"STOR {file}"
        response = ftp.storbinary(stor_command, fp=infile)
        print(response)

print(ftp.cwd('~/htdocs/includes'))
for file in files_to_update['includes']:
    with open(f"includes/{file}", 'rb') as infile:
        print(f"Transferring {file}")
        stor_command = f"STOR {file}"
        response = ftp.storbinary(stor_command, fp=infile)
        print(response)

for dir in files_to_update['assets']:
    directory = files_to_update['assets'][dir]
    try:
        print(ftp.cwd(f'~/htdocs/assets/{dir}'))
    except:
        ftp.mkd(f'~/htdocs/assets/{dir}')
        print(ftp.cwd(f'~/htdocs/assets/{dir}'))
    for file in directory:
        with open(f"assets/{dir}/{file}", 'rb') as infile:
            print(f"Transferring {file}")
            stor_command = f"STOR {file}"
            response = ftp.storbinary(stor_command, fp=infile)
            print(response)

print(ftp.cwd(f'~/htdocs/css'))
for file in files_to_update['css']:
    with open(f"css/{file}", 'rb') as infile:
        print(f"Transferring {file}")
        stor_command = f"STOR {file}"
        response = ftp.storbinary(stor_command, fp=infile)
        print(response)


for dir in files_to_update['api']:
    directory = files_to_update['api'][dir]
    try:
        print(ftp.cwd(f'~/htdocs/api/{dir}'))
    except:
        print(ftp.cwd(f'~/htdocs/api'))
        ftp.mkd(f'{dir}')
        print(ftp.cwd(f'~/htdocs/api/{dir}'))
    for file in directory:
        with open(f"api/{dir}/{file}", 'rb') as infile:
            print(f"Transferring {file}")
            stor_command = f"STOR {file}"
            response = ftp.storbinary(stor_command, fp=infile)
            print(response)

ftp.close()
