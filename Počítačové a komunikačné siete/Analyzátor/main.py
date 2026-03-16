from scapy.all import *
from binascii import *
from collections import Counter

def space(p_string, n=2):
    p_string = str(p_string)
    return ' '.join(p_string[i:i+n] for i in range(0, len(p_string), n))
#files
prot=open("Inner_protocol.txt","r")
ipf=open("ip.txt","r")
tcp=open("tcp.txt","r")
icmp=open("icmp.txt","r")
udp=open("udp.txt","r")
end=1
a=0
trgip=''
while int(end)!=0:
    out = open("answer.txt", "w")
    a=input("Zadaj číslo trace súboru: ")
    inpt=input("Zadaj \"all\" pre celý výpis alebo filtrovaciu podmienku:")
    file=rdpcap('C:/Users/42190\Desktop/vzorky_pcap_na_analyzu/trace-'+str(a)+'.pcap')
    print('Dĺžka súboru',len(file))
    out.write("Dĺžka súboru "+(str(len(file)))+"\n")
    queue=0
    count=0
    srclist=[]
    for packet in file:
        queue+=1
        leng=int(len((hexlify(raw(packet)).decode()))/2)
        new=str((hexlify(raw(packet)).decode()))  # v riadku

        #API len
        if (leng<=60):
           leng2=64
        else:
           leng2=leng+2

        #Frame type
        frame=new[24:28]
        if((int(frame, 16))>=1536):
            frametype="Ethernet II"
            type=frame
        elif(new[28:30]=='aa'):
            frametype="IEEE 802.3 LLC-Snap"
            type=new[40:44]
        elif (new[28:30] == 'ff'):
            frametype = "IEEE 802.3-Raw"
            type=new[28:30]
        else:
            frametype="IEEE 802.3 LLC"
            type=new[28:30]

        #MAC
        src = str(new[12:24])
        dst = new[0:12]
        src=(space(src)).upper()
        dst=(space(dst)).upper()

        #Inner protocol
        prot.seek(0)
        inp=''
        ethertype=''
        for line in prot:
            x=line[0:7]
            inp=line[7:len(line)-1]
            if((int(type,16))==(int(x,16))):
                ethertype=inp
                break
        #declaration of var
        srcip = ''
        dstip = ''
        srcp=0
        dstp=0
        protocol='Nenašlo'
        protocol1='Nenašlo'

        # IP adress
        if('IPv4' in inp):
            for i in range(4):
                srcip+=(str(int(new[52+2*i:54+2*i],16)))
                dstip += (str(int(new[60 + 2 * i:62 + 2 * i], 16)))
                if(i<3):
                    srcip+='.'
                    dstip+='.'

            #protocol
            iphead=new[46:48]
            ipf.seek(0)
            for line in ipf:
                x,inp=line.split()
                if (int(iphead,16)==int(x)):
                    protocol=inp
                    break
            ihl=int(new[29:30],16)
            #ICMP
            if('ICMP' in inp):
                icmptype = int(new[28 + ihl * 8:30 + ihl * 8], 16)
                icmp.seek(0)
                for line in icmp:
                    x, ictype = line.split(" ", 1)
                    if (icmptype == int(x)):
                        protocol1=ictype[0:len(ictype)-1]
                        break

            #TCP
            if ('TCP' in inp):
                srcp=int(new[28+ihl*8:32+ihl*8],16)
                dstp=int(new[32+ihl*8:36+ihl*8],16)
                if(srcp>dstp):
                    prt=dstp
                else:
                    prt=srcp
                tcp.seek(0)
                for line in tcp:
                    x, port = line.split(" ",1)
                    if (prt == int(x)):
                        protocol1=port[0:len(port)-1]
                        break
            #UDP
            if ('UDP' in inp):
                srcp = int(new[28 + ihl * 8:32 + ihl * 8], 16)
                dstp = int(new[32 + ihl * 8:36 + ihl * 8], 16)
                if (srcp > dstp):
                    prt = dstp
                else:
                    prt = srcp
                udp.seek(0)
                for line in udp:
                    x, port = line.split(" ", 1)
                    if (prt == int(x)):
                        protocol1=port[0:len(port)-1]
                        break


        def arp(trgip):
            sip=''
            tip=''
            op=int(new[40:44],16)
            for i in range(4):
                sip += (str(int(new[56 + 2 * i:58 + 2 * i], 16)))
                tip += (str(int(new[76 + 2 * i:78 + 2 * i], 16)))
                if (i < 3):
                    sip += '.'
                    tip += '.'
            if (op == 1):
                print("Request")
                out.write("Request\n")
                trgip = tip
            else:
                print("Reply")
                out.write("Reply\n")
                if(trgip==sip):
                    print("Komunikácia s predchádzajúcim")
                    out.write("Komunikácia s predchádzajúcim\n")
                    trgip=''

            print("Sender ip:",sip)
            print("Target ip:",tip)
            out.write("Sender ip: "+sip+"\n")
            out.write("Target ip: "+tip+"\n")


            return trgip


        #Output + filters
        if(inpt=="all" or (inpt.upper() in protocol.upper()) or (inpt.upper() in protocol1.upper())or (inpt.upper() in ethertype.upper())):
            count+=1
            print("Rámec", queue)
            out.write("Rámec " + str(queue) + "\n")
            print("Dĺžka rámca poskytnutá pcap API:", leng, "B")
            out.write("Dĺžka rámca poskytnutá pcap API: " + str(leng) + "B\n")
            print("Dĺžka rámca prenášaného po médiu:", leng2, "B")
            out.write("Dĺžka rámca prenášaného po médiu: " + str(leng2) + "B\n")
            print("Rámec:",frametype)
            out.write("Rámec: "+str(frametype)+"\n")
            print("Zdrojová MAC adresa:", src)
            out.write("Zdrojová MAC adresa:" + str(src) + "\n")
            print("Cieľová MAC adresa: " + str(dst))
            out.write("Cieľová MAC adresa: " + str(dst) + "\n")
            print(ethertype)
            out.write(ethertype+ "\n")
            if((dstip!='') and (srcip!='')):
                srclist.append(srcip)
                print("Zdrojová IP adresa:", srcip)
                print("Cieľová IP adresa:", dstip)
                out.write("Zdrojová IP adresa: " + srcip + "\n")
                out.write("Cieľová IP adresa: " + dstip + "\n")
                print(protocol)
                out.write(protocol + "\n")
                print(protocol1)
                out.write(protocol1+ "\n")
                if (srcp != 0 and dstp != 0):
                    print("Zdrojový port:", srcp)
                    print("Cieľový port:", dstp)
                    out.write("Zdrojový port: " + str(srcp) + "\n")
                    out.write("Cieľový port: " + str(dstp) + "\n")
            if(inpt.upper()=="ARP"):
                trgip=arp(trgip)




        #Frame output
            for i in range(int(leng/16)):
                output=new[0:32]
                new=new[32:len(new)]
                output=space(output)
                output=output[:24]+' '+output[24:]
                print(output)
                out.write(str(output)+"\n")
                if((len(new))<32):
                    new=space(new)
                    new = new[:24] + ' ' + new[24:]
                    print(new)
                    out.write(str(new)+"\n")
            print()
            out.write("\n")


    #IP node
    print("Počet vypísaných protokolov:",count)
    list=[]
    for ip in srclist:
        multiple=False
        multiple=any(ip in obj for obj in list)
        if (multiple==False):
            list.append(ip)
    print("IP adresy vysielajúcich uzlov:")
    out.write("IP adresy vysielajúcich uzlov:\n")
    for ip in list:
        print(ip)
        out.write(ip+"\n")
    cnt= Counter(srclist)
    mostip=str(cnt.most_common(1))
    mostip=mostip[3:len(mostip)-2]
    mostip=mostip.replace("'","")
    mostip=mostip.replace(",","")
    print("Adresa uzla s najväčším počtom odoslaných paketov:\n",mostip)
    out.write("Adresa uzla s najväčším počtom odoslaných paketov:\n"+mostip)
    end=input("Pre koniec zadaj 0 pre pokračovanie 1:")
    out.close()


