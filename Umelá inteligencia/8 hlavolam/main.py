import copy
import time
import datetime
all_node=0
class Node:
    def __init__(self):
        self.moves =""
        self.right = None
        self.left = None
        self.up = None
        self.down =None
        self.depth = 0
        self.matrix = []

def solvable(Matrix,Matrix1):                                   # if is possible to find way from start to finish
    N1 = 0
    N2 = 0
    for a in range(8):
        for b in range(a, 9):
            if (Matrix[a] > Matrix[b]) and Matrix[b] != 0 and Matrix[a] != 0:
                N1 += 1
            if (Matrix1[a] > Matrix1[b]) and Matrix1[b] != 0 and Matrix[a] != 0:
                N2 += 1
    if(N1 % 2 == N2 % 2):
        print("Hlavolam je riešitelný")
    else:
        time.sleep(1)
        exit("Neriešitelná kombinácia")
def pos0(Matrix):
   return (Matrix.index(0))

def up(node,mat_all):
    global all_node
    Matrix=node.matrix.copy()
    child = Node()
    child.moves=node.moves+'u'
    child.depth=node.depth + 1
    empty=pos0(Matrix)
    if(empty>2):
        copy = Matrix[empty - 3]
        Matrix[empty - 3] = 0
        Matrix[empty] = copy
        if Matrix in mat_all:
            return (None, mat_all)
        mat_all.append(Matrix.copy())
        child.matrix=Matrix.copy()
        all_node+=1
        return (child,mat_all)
    return (None,mat_all)

def down(node, mat_all):
    global all_node
    Matrix = node.matrix.copy()
    child = Node()
    child.moves=node.moves+'d'
    child.depth = node.depth + 1
    empty = pos0(Matrix)
    if (empty < 6):
        copy = Matrix[empty + 3]
        Matrix[empty + 3] = 0
        Matrix[empty] = copy
        if Matrix in mat_all:
            return (None, mat_all)
        mat_all.append(Matrix.copy())
        child.matrix = Matrix.copy()
        all_node +=1
        return (child,mat_all)
    return (None,mat_all)

def right(node,mat_all):
    global all_node
    Matrix = node.matrix.copy()
    child = Node()
    child.moves=node.moves+ 'r'
    child.depth = node.depth + 1
    empty = pos0(Matrix)
    if (empty!=(2 and 5 and 8)):
        copy = Matrix[empty + 1]
        Matrix[empty + 1] = 0
        Matrix[empty] = copy
        if Matrix in mat_all:
            return (None, mat_all)
        mat_all.append(Matrix.copy())
        child.matrix = Matrix.copy()
        all_node += 1
        return (child,mat_all)
    return (None,mat_all)

def left(node,mat_all):
    global all_node
    Matrix = node.matrix.copy()
    child = Node()
    child.moves=node.moves+ 'l'
    child.depth = node.depth + 1
    empty = pos0(Matrix)
    if (empty%3!=0):
        copy = Matrix[empty - 1]
        Matrix[empty - 1] = 0
        Matrix[empty] = copy
        if Matrix in mat_all:
            return (None, mat_all)
        mat_all.append(Matrix.copy())
        child.matrix = Matrix.copy()
        all_node += 1
        return (child,mat_all)
    return (None,mat_all)

def output(matrix):
    x=0
    for prvok in matrix:
        print(prvok,end="")
        #slice.join(map(str,prvok))
        x+=1
        if(x%3==0):
            print('')
    print('')
#Skuska()
def solution(children1,children2):  #comparing of state from both trees
    global all_node
    for child1 in children1:
        for child2 in children2:
            if (child1.matrix==child2.matrix):
                dt = datetime.datetime.now()
                ms = float(dt.microsecond / 1000000)
                sec = int(dt.second)
                min = int(dt.minute)
                finish = sec + 60 * min + ms
                print("Čas ",finish-start," s")
                print("riešenie nájdené")
                print("hĺbka 1. stromu",child1.depth)
                print("hĺbka 2. stromu",child2.depth)
                print("kde sa stromy stretli")
                output(child1.matrix)
                print("Vytvorené uzly: ",all_node)
                move=child2.moves[::-1]
                move2=""
                for a in move:
                    match a:
                        case 'u':
                            move2+='d'
                    match a:
                        case 'd':
                            move2 += 'u'
                    match a:
                        case 'r':
                            move2 += 'l'
                    match a:
                        case 'l':
                            move2 += 'r'

                print("Všetky kroky: ",child1.moves+move2)
                time.sleep(1)
                exit()

def search(Matrix,Matrix1):           #search function
    node1 = Node()
    node2 = Node()
    node1.matrix = copy.deepcopy(Matrix)
    node2.matrix = copy.deepcopy(Matrix1)
    parents1 = []
    parents2 = []
    parents1.append(node1)
    parents2.append(node2)
    children1 = []
    children2 = []
    mat1all = []
    mat2all = []
    mat1all.append(Matrix.copy())
    mat2all.append(Matrix1.copy())
    while True:
        children1.clear()
        children2.clear()
        for parent in parents1:      #cycle for finding parents from input matrix
            parent.up,mat1all = up(parent,mat1all)
            if parent.up is not None:
                children1.append(parent.up)

            parent.down,mat1all = down(parent,mat1all)
            if parent.down is not None:
                children1.append(parent.down)

            parent.left,mat1all = left(parent,mat1all)
            if parent.left is not None:
                children1.append(parent.left)

            parent.right,mat1all = right(parent,mat1all)
            if parent.right is not None:
                children1.append(parent.right)

        solution(children1,parents2)
        for parent in parents2:      #cycle for finding parents from output matrix
            parent.up,mat2all = up(parent,mat2all)
            if parent.up is not None:
                children2.append(parent.up)

            parent.down,mat2all = down(parent,mat2all)
            if parent.down is not None:
                children2.append(parent.down)

            parent.left,mat2all = left(parent,mat2all)
            if parent.left is not None:
                children2.append(parent.left)

            parent.right,mat2all = right(parent,mat2all)
            if parent.right is not None:
                children2.append(parent.right)

        parents1.clear()
        parents2.clear()
        parents1=children1.copy()
        parents2=children2.copy()
        solution(children1,children2)

            



#starting point of the program------------------------------------------------------------------------------------------


Matrix = [0 for a in range(9)]
Matrix1 = [0 for a in range(9)]
order = 1
#reading input and output data
while True:
    indata=input("Zadaj vstupný hlavolam:")
    if(len(indata)==9):
        break

while True:
    outdata=input("Zadaj výstupný hlavolam:")
    if(len(outdata)==9):
        break
# root=search(copy.deepcopy(Matrix),x,y,size,order,timer,start)

x=0
for a in indata:
    Matrix[x]=int(a)
    x+=1

x=0
for a in outdata:
    Matrix1[x]=int(a)
    x+=1
solvable(Matrix,Matrix1)
dt=datetime.datetime.now()
ms=float(dt.microsecond/1000000)
sec=int(dt.second)
min=int(dt.minute)
start = sec + 60 * min+ms
search(Matrix,Matrix1)
#123456780


#///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

