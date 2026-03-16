import binascii
import socket
import os
import time
import threading
def header(info,seq,size,check):
    head=b''
    head+= int(info).to_bytes(1, byteorder='big')
    head+= int(seq).to_bytes(2, byteorder='big')
    head+= int(size).to_bytes(2, byteorder='big')
    head+= int(check).to_bytes(4, byteorder='big')
    return (head)
def head_i(info):
    head=b''
    head+= int(info).to_bytes(1, byteorder='big')
    head+= int(0).to_bytes(8, byteorder='big')
    return (head)
def datas(head):
    info = int.from_bytes(head[0:1], byteorder='big')
    seq = int.from_bytes(head[1:3], byteorder='big')
    size = int.from_bytes(head[3:5], byteorder='big')
    check = int.from_bytes(head[5:9], byteorder='big')
    return(info,seq,size,check)
def data_i(head):
    info = int.from_bytes(head[0:1], byteorder='big')
    return(info)
def sender(): #client ,send message
    IP="127.0.0.1"
    Port=4596
    IP=input("Zadaj IP:")
    Port=int(input("Zadaj Port:"))
    sendsoc=socket.socket(family=socket.AF_INET,type=socket.SOCK_DGRAM)
    sendsoc.sendto(head_i(0),(IP,Port))
    data, address = sendsoc.recvfrom(2000)
    while True:
        choice=send(address,sendsoc)
        if(choice==1):
            choice=get(address,sendsoc)
        else:
            break
        if (choice==0):
            break

    sendsoc.close

def send(address,sendsoc):
    shr=0
    shr = int(input("1-súbor,2-správa "))
    while True:
        if(shr==1):                                               #file---------------------
            fragment = int(input("Zadaj veľkosť fragmentu: "))
            broke = int(input("Zadaj poradie paketov ktoré sa pokazia: "))
            name='foto.jpg'
            name=input('Zadaj názov súboru: ')
            path = {'filepath': r'C:\Users\42190\PycharmProjects\Komunikátor',
                    'filename': None}
            path['filename']=name
            way=os.path.join(path['filepath'], path['filename'])
            file=open(way,'rb')
            filesize=os.path.getsize(path['filename'])
            sendsoc.sendto(head_i(1)+name.encode(),address)
            print('Cesta: ',path['filepath'],'Názov:',path['filename'])
            print('Veľkosť súboru',filesize,' B')
            seq=0
            test=0
            while (filesize):
                seq+=1
                if(filesize>=fragment):
                    filesize -=fragment
                    data_snd=file.read(fragment)
                    datacp=data_snd
                    head=header(3,seq,fragment,binascii.crc32(data_snd))
                    if(broke!=0):
                        if(seq % broke==0):
                            datacp = int.from_bytes(datacp, 'big')
                            datacp = datacp >> 1
                            datacp = datacp.to_bytes(fragment, 'big')
                            test+=1
                    sendsoc.sendto(head+datacp,address)
                    data=sendsoc.recv(2000)
                    info=data_i(data)
                    if(info!=4):
                        head = header(3, seq, fragment, binascii.crc32(data_snd))
                        sendsoc.sendto(head + data_snd, address)
                        data = sendsoc.recv(2000)

                else:
                    data_snd=file.read(filesize)
                    datacp = data_snd
                    head = header(3, seq, fragment,binascii.crc32(data_snd))
                    if (broke != 0):
                        if (seq % broke == 0):
                            datacp = int.from_bytes(datacp, 'big')
                            datacp = datacp >> 1
                            datacp = datacp.to_bytes(fragment, 'big')
                            test += 1
                    sendsoc.sendto(head + datacp, address)
                    data = sendsoc.recv(2000)
                    info = data_i(data)

                    if (info != 4):
                        head = header(3, seq, fragment, binascii.crc32(data_snd))
                        sendsoc.sendto(head + data_snd, address)
                        data = sendsoc.recv(2000)
                    break
            sendsoc.sendto(head_i(6), address)
            print('Počet paketov: ',seq)
            print('Počet chybných paketov: ',test)

        if(shr==2):                                           #msg--------------------------
            fragment = int(input("Zadaj veľkosť fragmentu: "))
            msg=input("Zadaj spávu: ")
            seq=0
            msg=bytearray(msg.encode())
            sendsoc.sendto(head_i(2), address)
            while (len(msg)>fragment):
                seq+=1
                sendsoc.sendto(header(3,seq,fragment,0)+msg[:fragment],address)
                msg=msg[fragment:]
            seq += 1
            sendsoc.sendto(header(3,seq,fragment,0)+msg, address)
            print('Počet paketov: ',seq)
            sendsoc.sendto(head_i(6), address)
        global ka_on
        ka_on=True
        ka=threading.Thread(target=keep_alive,args=(address,sendsoc))
        ka.daemon=True
        ka.start()
        shr = int(input("1-súbor,2-správa,3-prehodenie,4-koniec:"))
        ka_on=False
        ka.join()
        if (shr == 3):
            sendsoc.sendto(head_i(8), address)
            return(1)
        if (shr == 4):
            sendsoc.sendto(head_i(9),address)
            return(0)

ka_on=True
def keep_alive(address,sendsoc):
    while ka_on==True:
        sendsoc.sendto(head_i(7),address)
        data=sendsoc.recv(200)
        time.sleep(10)



def reciver(): #sever (bind)
    Port=4596
    recsoc=socket.socket(family=socket.AF_INET,type=socket.SOCK_DGRAM)
    IP="127.0.0.1"
    IP=input("Zadaj IP:")
    Port=int(input("Zadaj Port:"))
    recsoc.bind((IP,Port))
    data, address = recsoc.recvfrom(20000)
    if(data_i(data[0:9])==0):
        recsoc.sendto(head_i(0),address)
    while True:
        choice = get(address, recsoc)
        if (choice == 1):
            choice = send(address, recsoc)
        else:
            break
        if (choice == 0):
            break
    recsoc.close()

def get(address,recsoc):
    data=recsoc.recv(2000)
    while True:
        if (data_i(data[0:9]) == 1):         #reciving file
            path = {'filepath': r'C:\Users\42190\PycharmProjects\Komunikátor',
                    'filename': None}
            name=data[9:].decode()
            path['filename'] = "copy_"+name
            way=os.path.join(path['filepath'],path['filename'])
            file = open(way,"wb")
            paket=0
            mist=0
            while True:
                data = recsoc.recv(20000)
                head= data[0:9]
                data=data[9:]
                info,seq,ize,check=datas(head)
                if(info == 6):
                    file.close()
                    break
                if(check==binascii.crc32(data)):
                    paket+=1
                    file.write(data)
                    recsoc.sendto(head_i(4), address)
                    print(seq,' dobre')
                else:
                    recsoc.sendto(head_i(5),address)
                    mist+=1
                    print(seq,' chyba')

            filesize = os.path.getsize(path['filename'])
            print('Cesta: ', path['filepath'], 'Názov:', path['filename'])
            print('Veľkosť súboru', filesize, ' B')
            print('Dobre poslané pakety: ',paket)
            print('Zle poslané pakety: ',mist)

        if (data_i(data[0:9]) == 2):       #reciving msg
            msg=bytearray()
            paket=0
            while True:
                data = recsoc.recv(20000)
                if(data_i(data[0:9]) == 6):
                    print(msg.decode())
                    print('Počet paketov: ',paket)
                    break
                msg+=data[9:]
                paket+=1
        data=recsoc.recv(2000)
        if (data_i(data[0:1]) == 7): #keep alive
            recsoc.sendto(head_i(7),address)
        if (data_i(data[0:1])==8): #change
            return(1)
        if (data_i(data[0:1]) == 9): #end
            return(0)


#Main-------------------------------------------------------------------------------------------------------------------

step=0
while (step!=3):
    step=int(input("1-vysielač,2-prímač,3-koniec "))
    if (step==1):
        sender()
    elif (step==2):
        reciver()



