import copy
import time
from random import *
from math import *
import matplotlib.pyplot as plt
from tkinter import *
import tkinter as tk
#data representing
class Child:
    def __init__(self):
        self.distance = None
        self.cyty = None

best_cyty=[]
best_cyty_sim=Child()

tabugraph=[0]
simugraph=[0]
# output of best path and generate graph

def fitness(arr,cyties):
    length=0
    for a in range(-1, len(cyties)-1):
        #count of euclid distance between 2 cyties
        b=sqrt(((arr[0][cyties[a]]-arr[0][cyties[a+1]])**2)+((arr[1][cyties[a]]-arr[1][cyties[a+1]])**2))
        length+=b
    return length

def tabu_search(arr,tabu,tabu_list,cyties,generation):
    cyty_path=cyties.copy()
    if generation==0:
        return()
    dist=fitness(arr,cyties)
    children = []
    #creating of new nodes
    for a in range(1,len(cyties)-1):
        child=Child()

        cyties_copy=cyties.copy()
        cop=cyties_copy[a]
        cyties_copy[a]=cyties_copy[a+1]
        cyties_copy[a+1]=cop
        if cyties_copy  not in tabu_list:
            child.cyty=cyties_copy
            child.distance=fitness(arr, cyties_copy)
            children.append(child)
    cyty_best=Child()
    cyty_best.distance = children[0].distance
    cyty_best.cyty = children[0].cyty
    for a in children:
        if(cyty_best.distance>a.distance):
            cyty_best.cyty=a.cyty
            cyty_best.distance=(a.distance)
    global tabugraph
    global way
    tabugraph.append(way-cyty_best.distance)
    #if better way was found
    if(cyty_best.distance<dist):
        global best_cyty
        best_cyty = (cyty_best.cyty).copy()
        tabu_search(arr,tabu,tabu_list,cyty_best.cyty,generation-1)

    #founded way is the same or worse
    else:
        tabu_list.append(cyties)
        if(len(tabu_list)>tabu):
            tabu_list.pop(0)
        tabu_search(arr,tabu,tabu_list,cyty_best.cyty,generation-1)
    return ()






def simulated_annealing(arr,temp,cyties):
    if(temp<0.1):
        return()
    global best_cyty_sim
    global simugraph
    global way
    if not best_cyty_sim.cyty:
        best_cyty_sim.cyty=cyties.copy()
        best_cyty_sim.distance=fitness(arr,cyties)
    dist = fitness(arr, cyties)
    #creating children
    for a in range(1,len(cyties)-1):
        cyties_new = cyties.copy()
        cop = cyties_new[a]
        cyties_new[a] = cyties_new[a + 1]
        cyties_new[a + 1] = cop
        # better solution was found
        diff = fitness(arr, cyties_new) - fitness(arr, cyties)

        if(fitness(arr,cyties_new)<dist):
            best_cyty_sim.cyty = cyties.copy()
            simugraph.append(way - fitness(arr, cyties_new))
            best_cyty_sim.distance = fitness(arr, cyties)
            simulated_annealing(arr,temp*0.95,cyties_new)
            return ()
        elif(fitness(arr,cyties_new)>dist):
            crit=float(exp(-diff/temp))
            if(crit.real>(randint(0,100))/100):
                simugraph.append(way - fitness(arr, cyties_new))
                simulated_annealing(arr,temp*0.95,cyties_new)
                return()
    return ()



# reading of input

places=int(input("Zadaj počet uzlov "))
size=int(input("Zadaj rozmer mapy (max 650) "))
generation=int(input("Zadaj počet generácii "))
tabu=int(input("Zadaj veľkosť tabu listu "))
temp=int(input("Zadaj počiatočnú teplotu "))


arr =  [[0 for i in range(places)] for j in range(2)]
#random creating cities
for a in range(places):
    arr[0][a]=5+randrange(size)
for a in range(places):
    arr[1][a]=5+randrange(size)

cyties=list(range(0,places))
way=fitness(arr,cyties)

tabu_list=[]
start=time.time()
tabu_search(arr,tabu,tabu_list,cyties,generation)
end=time.time()
print('Tabu')
print("Čas ",end-start)
print('Počiatočná trasa',fitness(arr,cyties))
print("Najdene tabu ",fitness(arr,best_cyty))
print('Zlepšenie ',100-((fitness(arr,best_cyty)*100)/((fitness(arr,cyties)))),' % \n')


start=time.time()
simulated_annealing(arr,temp,cyties)
end=time.time()

print('Žíhanie')
print('Čas ', end-start)
print('Počiatočná trasa',fitness(arr,cyties))
print("Nájdene simulované žíhanie ",best_cyty_sim.distance)
print('Zlepšenie ',100-((best_cyty_sim.distance*100)/((fitness(arr,cyties)))),' %')

#graphs
fig,a=plt.subplots(1,2)
a[0].set_title("Zakázané prehľadávanie")
a[0].plot(tabugraph)
a[0].set(xlabel='generácia', ylabel='zmena vo vzdialenosti')
a[1].set_title("Simulované žíhanie")
a[1].plot(simugraph)
a[1].set(xlabel='generácia', ylabel='zmena vo vzdialenosti')


plt.tight_layout()
plt.show()


master = Tk()
master.title("Porovnanie vstupu a tabu")
w = Canvas(master, width=700, height=800)
w.grid(row=0,column=0)
o = Canvas(master, width=700, height=800)
o.grid(row=0,column=1)

#input way
for a in range(0,places):
    w.create_oval((arr[0][a]),(arr[1][a]),(arr[0][a])+5,(arr[1][a])+5, fill="black")
    w.create_text((arr[0][a]),(arr[1][a]),
                  fill="black", font="Times 10 italic bold", text=str(a+1))


for a in range(-1, len(cyties)-1):
    w.create_line((arr[0][cyties[a]]),(arr[1][cyties[a]]),(arr[0][cyties[a+1]]),(arr[1][cyties[a+1]]), width="2",arrow=tk.LAST)

#output way

for a in range(0,places):
    o.create_oval((arr[0][a]),(arr[1][a]),(arr[0][a])+5,(arr[1][a])+5, fill="black")
    o.create_text((arr[0][a]),(arr[1][a]),
                  fill="black", font="Times 10 italic bold", text=str(a+1))


for a in range(-1, len(best_cyty)-1):
    o.create_line((arr[0][best_cyty[a]]),(arr[1][best_cyty[a]]),(arr[0][best_cyty[a+1]]),(arr[1][best_cyty[a+1]]), width="2",arrow=tk.LAST)


master.mainloop()


maste = Tk()
maste.title("Porovnanie vstupu a simulovaného žíhania")
f = Canvas(maste, width=700, height=800)
f.grid(row=0,column=0)
g = Canvas(maste, width=700, height=800)
g.grid(row=0,column=1)

#input way

for a in range(0,places):
    f.create_oval((arr[0][a]),(arr[1][a]),(arr[0][a])+5,(arr[1][a])+5, fill="black")
    f.create_text((arr[0][a]),(arr[1][a]),
                  fill="black", font="Times 10 italic bold", text=str(a+1))

for a in range(-1, len(cyties)-1):
    f.create_line((arr[0][cyties[a]]),(arr[1][cyties[a]]),(arr[0][cyties[a+1]]),(arr[1][cyties[a+1]]), width="2",arrow=tk.LAST)

#output way

for a in range(0,places):
    g.create_oval((arr[0][a]),(arr[1][a]),(arr[0][a])+5,(arr[1][a])+5, fill="black")
    g.create_text((arr[0][a]),(arr[1][a]),
                  fill="black", font="Times 10 italic bold", text=str(a+1))

best_cyty=(best_cyty_sim.cyty).copy()
for a in range(-1, len(best_cyty)-1):
    g.create_line((arr[0][best_cyty[a]]),(arr[1][best_cyty[a]]),(arr[0][best_cyty[a+1]]),(arr[1][best_cyty[a+1]]), width="2",arrow=tk.LAST)



maste.mainloop()












