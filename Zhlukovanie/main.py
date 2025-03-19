import copy
import time
import matplotlib.pyplot as plt
from numpy import *
from random import *

class Cluster:
    def __init__(self):
        self.center=[]
        self.points=[]


def create_points(start_points,all_points,max_range):
    points = []
    start = time.time()
    # generating first 20 points
    while (len(points) < start_points):
        x = (randrange(-max_range, max_range), randrange(-max_range, max_range))
        if x not in points:
            points.append(x)

    save = points.copy()

    # generating next 20000 points

    while (len(points) < all_points):
        y = randrange(len(points))
        dot = points[y]
        if ((max_range - abs(dot[0]) < 100) or (max_range - abs(dot[1]) < 100)):
            d1 = 0
            d2 = 0
            d3 = 0
            d4 = 0
            if (dot[0] < -max_range+100):
                d1 = dot[0] + max_range
            if (dot[0] > max_range-100):
                d1 = max_range - dot[0]
            if (dot[1] < -max_range+100):
                d1 = dot[1] + max_range
            if (dot[1] > max_range-100):
                d1 = max_range - dot[1]

            offset = (randrange(-100 + d1, 100 - d2), randrange(-100 + d3, 100 - d4))
            new = sum([dot, offset], axis=0)
            points.append(new)

        else:
            offset = (randrange(-100, 100), randrange(-100, 100))
            new = sum([dot, offset], axis=0)
            points.append(new)

    return(points)


def centroid(cluster):
    x=0
    y=0

    for point in cluster.points:
        x+=point[0]
        y+=point[1]
    x=x/len(cluster.points)
    y=y/len(cluster.points)
    cluster.center=(x,y)

    return (cluster)


def medoid(cluster):
    distances=[]

    for point1 in cluster.points:
        distance=0
        for point2 in cluster.points:
            distance+= sqrt(((point1[0] - point2[0]) ** 2) + ((point1[1] - point2[1]) ** 2))
        distances.append(distance)

    cluster.center=cluster.points[distances.index(min(distances))]




    return (cluster)


def min_distance(distances):
    min=amax(distances)
    cluster1,cluster2=unravel_index(argmax(distances),shape(distances))
    for x in range(len(distances)):
        for y in range(x+1, len(distances)+1):
           new=distances[x][y]
           if(distances[x][y])<min:
               min=distances[x][y]
               cluster1=x
               cluster2=y


    return(cluster1,cluster2)


def aglomerative_clustering(all_points,cluster_total,center):
    #creating of clusters
    clusters=[]
    for a in range(len(points)):
        cluster = Cluster()
        cluster.center = copy(points[a])
        cluster.points.append(points[a])
        clusters.append(cluster)
    #creating matrix with all distances
    distances = [[0 for i in range(len(clusters))] for j in range(len(clusters)-1)]
    for x in range(len(all_points)):
        for y in range(x+1, len(all_points)):
            # x od x y od y
            distances[x][y] = sqrt(((points[x][0] - points[y][0]) ** 2) + ((points[x][1] - points[y][1]) ** 2))

    while len(clusters)>cluster_total:
        #find a min
        cl1,cl2=min_distance(distances)
        #connect clusters
        for point in (clusters[cl2].points):
            clusters[cl1].points.append(point) #try with extend
        #find new center

        if center==1:
            clusters[cl1]=centroid(clusters[cl1])
        if center==2:
            clusters[cl1] = medoid(clusters[cl1])



        # delete unused
        del clusters[cl2]
        if(len(distances)<=cl2):
            del distances[cl2-1]
        else:
            del distances[cl2]
        for row in distances:
            del row[cl2]
        # calculate new distances in matrix for values cl1
        for a in range(cl1):
            distances[a][cl1]=sqrt((((clusters[cl1].center)[0] - (clusters[a].center)[0]) ** 2) + (((clusters[cl1].center)[1] - (clusters[a].center)[1]) ** 2))
            #distances[a][cl1]=9
        if(cl1!=len(distances)):
            for a in range(cl1+1,len(distances)+1):
                distances[cl1][a]=sqrt((((clusters[cl1].center)[0] - (clusters[a].center)[0]) ** 2) + (((clusters[cl1].center)[1] - (clusters[a].center)[1]) ** 2))
                #distances[cl1][a] = 6




    #average distance from center
    in_radius=0
    out_radius=0
    for clust in clusters:
        distance=0
        point2=clust.center
        for point in clust.points:
            distance+=sqrt(((point[0] - point2[0]) ** 2) + ((point[1] - point2[1]) ** 2))
        distance=distance/len(clust.points)
        if(distance<=500):
            in_radius+=1
        else:
            out_radius+=1

    print("Neúspešné klastre: ",out_radius,"\n Úspešné klastre: ",in_radius)




    return(clusters)



# main------------------------------------------------------------------------------------------------------------------
start_points=int(input('Zadaj hlavné body: '))
other_points=int(input('Zadaj ostatné body: '))
center=int(input('Zadaj 1 pre centroid a 2 pre monoid: '))
max_range=int(input('Zadaj veľkosť mapy: '))
cluster_total=int(input('Zadaj počet klastrov: '))



start=time.time()
all_points=start_points+other_points
points=create_points(start_points,all_points,max_range)



clusters=aglomerative_clustering(points,cluster_total,center)

print("Celkový čas (bez grafickej časti): ",time.time()-start)

# grafic part

colors=['red','green','purple','blue','orange','brown','yellow','pink','cyan']
color=0
for cluster in clusters:
    col='#{:02X}{:02X}{:02X}'.format(randrange(50,250),randrange(50,250),randrange(50,250))
    for a in range(len(cluster.points)):
        plt.scatter(cluster.points[a][0],cluster.points[a][1],c=col)
    color+=1



# centers
for cluster in clusters:
    plt.scatter(cluster.center[0],cluster.center[1],c='black')

plt.show()

