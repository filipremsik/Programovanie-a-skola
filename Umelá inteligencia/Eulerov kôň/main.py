import copy
import time
class Node:
    def __init__(self):
        self.rightdown = None
        self.rightup = None
        self.leftdown = None
        self.leftup = None
        self.downright = None
        self.downleft = None
        self.upright = None
        self.upleft = None
        self.order = 0
        self.matrix=0

#end when solution
def output(matrix,size):
    for line in matrix:
        print('  '.join(map(str, line)))
    print("\n")
    time.sleep(0.5)
    exit("Nájdené")

#end when time is out
def timeout(timer,start):
    min, sec = map(int, time.strftime("%M %S").split())
    end=sec+60*min
    if(end-start>timer):
        exit("Nenašlo")

def search(matrix,x,y,size,order,timer,start):
    node=Node()
    matrix[x][y]=order
    node.order=order
    timeout(timer,start)
    if(order==size*size):
        output(copy.deepcopy(matrix),size)

    if(order<size*size):
        #all possible moves
        if((node.rightdown==None) and (x+2<size) and (y+1<size) and (matrix[x+2][y+1]==0)):
            node.rightdown=search(copy.deepcopy(matrix),x+2,y+1,size,order+1,timer,start)
            node.rightdown =None

        if ((node.rightup == None) and (x + 2 < size) and (y - 1 >= 0) and (matrix[x + 2][y - 1] == 0)):
            node.rightup = search(copy.deepcopy(matrix), x + 2, y - 1, size, order + 1,timer,start)
            node.rightup =None

        if (node.leftdown == None and (x-2>=0) and (y+1<size) and (matrix[x-2][y+1]==0)):
            node.leftdown = search(copy.deepcopy(matrix),x-2,y+1,size,order+1,timer,start)
            node.leftdown =None

        if (node.leftup == None and (x-2 >= 0) and (y - 1 >= 0) and (matrix[x - 2][y - 1] == 0)):
            node.leftup = search(copy.deepcopy(matrix), x - 2, y - 1, size, order + 1,timer,start)
            node.leftup =None

        if ((node.downright == None) and (x + 1 < size) and (y + 2 < size) and (matrix[x + 1][y + 2] == 0)):
            node.downright = search(copy.deepcopy(matrix), x + 1, y + 2, size, order + 1,timer,start)
            node.downright =None

        if ((node.downleft == None) and (x - 1 >=0) and (y + 2 < size) and (matrix[x - 1][y + 2] == 0)):
            node.downleft = search(copy.deepcopy(matrix), x - 1, y + 2, size, order + 1,timer,start)
            node.downleft =None

        if (node.upright == None and (x + 1< size) and (y - 2 >= 0) and (matrix[x + 1][y - 2] == 0)):
            node.upright = search(copy.deepcopy(matrix), x + 1, y - 2, size, order + 1,timer,start)
            node.upright =None

        if (node.upleft == None and (x - 1 >= 0) and (y - 2 >= 0) and (matrix[x - 1][y - 2] == 0)):
            node.upleft = search(copy.deepcopy(matrix), x - 1, y - 2, size, order + 1,timer,start)
            node.upleft =None

    return(node)

x=int(input("Zadaj súradnicu x: "))
y =int(input("Zadaj súradnicu y: "))
size = int(input("Zadaj veľkosť šachovnice(5,6):"))
timer = int(input("Zadaj čas:"))
min,sec = map(int, time.strftime("%M %S").split())
start=sec+60*min
Matrix = [[0 for a in range(size)] for b in range(size)]
Matrix[x][y]=1
order=1
root=search(copy.deepcopy(Matrix),x,y,size,order,timer,start)
