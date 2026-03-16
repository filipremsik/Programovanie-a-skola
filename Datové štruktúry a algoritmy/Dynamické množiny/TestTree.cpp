#define _CRT_SECURE_NO_WARNINGS
#include<stdio.h>
#include<stdlib.h>
#include <time.h>

enum type { RED, BLACK };

struct Node
{
    int data;
    struct Node* left;
    struct Node* right;
    struct Node* parent;
    enum type color;
};

struct Queue
{
    struct Node* data;
    struct Queue* next;
};

struct Queue* front = NULL;
struct Queue* rear = NULL;

struct Node* pfront()
{
    struct Node* data;
    data = front->data;
    return data;
}

int isempty()
{
    if (front == NULL)
        return 1;

    else
        return 0;
}

void dequeue()
{
    if (isempty())
        return;

    struct Queue* temp = front;
    front = front->next;
    free(temp);
}


void enqueue(struct Node* data)
{
    struct Queue* temp = (struct Queue*)malloc(sizeof(struct Queue));
    temp->data = data;
    temp->next = NULL;

    if (front == NULL && rear == NULL)
    {
        front = rear = temp;
        return;
    }

    rear->next = temp;
    rear = temp;
}

void levelorder(struct Node* root)
{
    if (root == NULL)
        return;

    enqueue(root);

    while (!isempty())
    {
        struct Node* current = pfront();
        printf("%d ", current->data);

        if (current->left != NULL)
            enqueue(current->left);

        if (current->right != NULL)
            enqueue(current->right);

        dequeue();
    }
}

void LeftRotate(struct Node** T, struct Node** x)
{
    struct Node* y = (*x)->right;
    (*x)->right = y->left;

    if (y->left != NULL)
        y->left->parent = *x;

    y->parent = (*x)->parent;

    if ((*x)->parent == NULL)
        *T = y;

    else if (*x == (*x)->parent->left)
        (*x)->parent->left = y;

    else
        (*x)->parent->right = y;

    y->left = *x;

    (*x)->parent = y;

}
void RightRotate(struct Node** T, struct Node** x)
{
    struct Node* y = (*x)->left;
    (*x)->left = y->right;

    if (y->right != NULL)
        y->right->parent = *x;

    y->parent = (*x)->parent;

    if ((*x)->parent == NULL)
        *T = y;

    else if ((*x) == (*x)->parent->left)
        (*x)->parent->left = y;

    else
        (*x)->parent->right = y;

    y->right = *x;
    (*x)->parent = y;

}

void RB_insert_fixup(struct Node** T, struct Node** z)
{
    struct Node* grandparent = NULL;
    struct Node* parentpt = NULL;

    while (((*z) != *T) && ((*z)->color != BLACK) && ((*z)->parent->color == RED))
    {
        parentpt = (*z)->parent;
        grandparent = (*z)->parent->parent;

        if (parentpt == grandparent->left)
        {
            struct Node* uncle = grandparent->right;

            if (uncle != NULL && uncle->color == RED)
            {
                grandparent->color = RED;
                parentpt->color = BLACK;
                uncle->color = BLACK;
                *z = grandparent;
            }

            else
            {
                if ((*z) == parentpt->right)
                {
                    LeftRotate(T, &parentpt);
                    (*z) = parentpt;
                    parentpt = (*z)->parent;
                }

                RightRotate(T, &grandparent);
                parentpt->color = BLACK;
                grandparent->color = RED;
                (*z) = parentpt;
            }
        }

        else
        {
            struct Node* uncle = grandparent->left;

            if (uncle != NULL && uncle->color == RED)
            {
                grandparent->color = RED;
                parentpt->color = BLACK;
                uncle->color = BLACK;
                (*z) = grandparent;
            }

            else
            {
                if ((*z) == parentpt->left)
                {
                    RightRotate(T, &parentpt);
                    (*z) = parentpt;
                    parentpt = (*z)->parent;
                }

                LeftRotate(T, &grandparent);
                parentpt->color = BLACK;
                grandparent->color = RED;
                (*z) = parentpt;
            }
        }
    }
    (*T)->color = BLACK;

}

struct Node* RB_insert(struct Node* T, long long int data)
{
    struct Node* z = (struct Node*)malloc(sizeof(struct Node));
    z->data = data;
    z->left = NULL;
    z->right = NULL;
    z->parent = NULL;
    z->color = RED;

    struct Node* y = NULL;
    struct Node* x = T;//root

    while (x != NULL)
    {
        y = x;
        if (z->data < x->data)
            x = x->left;

        else
            x = x->right;
    }
    z->parent = y;

    if (y == NULL)
        T = z;

    else if (z->data < y->data)
        y->left = z;

    else
        y->right = z;

    RB_insert_fixup(&T, &z);

    return T;
}

void preorder(struct Node* root)
{
    if (root == NULL)
        return;

    printf("%d ", root->data);
    preorder(root->left);
    preorder(root->right);
}

struct Node* Tree_minimum(struct Node* node)
{
    while (node->left != NULL)
        node = node->left;

    return node;
}

void RB_delete_fixup(struct Node** T, struct Node** x)
{
    while ((*x) != *T && (*x)->color == BLACK)
    {
        if ((*x) == (*x)->parent->left)
        {
            struct Node* w = (*x)->parent->right;

            if (w->color == RED)
            {
                w->color = BLACK;
                (*x)->parent->color = BLACK;
                LeftRotate(T, &((*x)->parent));
                w = (*x)->parent->right;
            }

            if (w->left->color == BLACK && w->right->color == BLACK)
            {
                w->color = RED;
                (*x) = (*x)->parent;
            }

            else
            {
                if (w->right->color == BLACK)
                {
                    w->left->color = BLACK;
                    w->color = RED;
                    RightRotate(T, &w);
                    w = (*x)->parent->right;
                }

                w->color = (*x)->parent->color;
                (*x)->parent->color = BLACK;
                w->right->color = BLACK;
                LeftRotate(T, &((*x)->parent));
                (*x) = *T;
            }
        }

        else
        {
            struct Node* w = (*x)->parent->left;

            if (w->color == RED)
            {
                w->color = BLACK;
                (*x)->parent->color = BLACK;
                RightRotate(T, &((*x)->parent));
                w = (*x)->parent->left;
            }

            if (w->right->color == BLACK && w->left->color == BLACK)
            {
                w->color = RED;
                (*x) = (*x)->parent;
            }

            else
            {
                if (w->left->color == BLACK)
                {
                    w->right->color = BLACK;
                    w->color = RED;
                    LeftRotate(T, &w);
                    w = (*x)->parent->left;
                }

                w->color = (*x)->parent->color;
                (*x)->parent->color = BLACK;
                w->left->color = BLACK;
                RightRotate(T, &((*x)->parent));
                (*x) = *T;
            }
        }
    }
    (*x)->color = BLACK;

}

void RB_transplat(struct Node** T, struct Node** u, struct Node** v)
{
    if ((*u)->parent == NULL)
        *T = *v;

    else if ((*u) == (*u)->parent->left)
        (*u)->parent->left = *v;
    else
        (*u)->parent->right = *v;

    if ((*v) != NULL)
        (*v)->parent = (*u)->parent;
}

struct Node* RB_delete(struct Node* T, struct Node* z)
{
    struct Node* y = z;
    enum type yoc;
    yoc = z->color; // y's original color

    struct Node* x;

    if (z->left == NULL)
    {
        x = z->right;
        RB_transplat(&T, &z, &(z->right));
    }

    else if (z->right == NULL)
    {
        x = z->left;
        RB_transplat(&T, &z, &(z->left));
    }

    else
    {
        y = Tree_minimum(z->right);
        yoc = y->color;
        x = y->right;

        if (y->parent == z)
            x->parent = y;

        else
        {
            RB_transplat(&T, &y, &(y->right));
            y->right = z->right;
            y->right->parent = y;
        }

        RB_transplat(&T, &z, &y);
        y->left = z->left;
        y->left->parent = y;
        y->color = z->color;
    }

    if (yoc == BLACK)
        RB_delete_fixup(&T, &x);

    return T;
}

int BST_search(struct Node* root, int x)
{
    if (root == NULL) {
        return (0);
    }
    if (root->data == x) {
        return (1);
    }

    if (root->data > x)
        return  BST_search(root->left, x);
    else
        return BST_search(root->right, x);
}
////////////////////////////////////////////////////////////////////////////////////////////////
typedef struct N0de {
	int key;
	int height;
	struct N0de* left;
	struct N0de* right;
}N0de;
int height(N0de* save) {
	if (save == NULL) {
		return(0);
	}
	return(save->height);
}

int max(int a, int b) {
	if (a > b) {
		return(a);
	}
	if (a < b) {
		return(b);
	}
	if (a == b) {
		return(a);
	}
}
N0de* rotateleft(N0de* tree) {
	N0de* newtree = tree->right;
	N0de* b = newtree->left;
	newtree->left = tree;		                                                                        // rotacia
	tree->right = b;
	tree->height = 1 + max(height(tree->left), height(tree->right));                                  // aktualizovanie vysok
	newtree->height = 1 + max(height(newtree->left), height(newtree->right));
	return(newtree);
}
N0de* rotateright(N0de* tree) {
	N0de* newtree = tree->left;
	N0de* b = newtree->right;
	newtree->right = tree;		                                                                       // rotacia
	tree->left = b;
	tree->height = 1 + max(height(tree->left), height(tree->right));		                      // aktualizovanie vysok
	newtree->height = 1 + max(height(newtree->left), height(newtree->right));

	return(newtree);
}

int balance(N0de* helpnode) {
	if (helpnode == NULL) {
		return(0);
	}

	return (height(helpnode->left) - height(helpnode->right));
}

N0de* insert(N0de* node, int key) {                                                     //volne miesto
	if (node == NULL) {
		N0de* nodenew = (N0de*)malloc(sizeof(N0de));
		nodenew->key = key;
		nodenew->left = NULL;
		nodenew->right = NULL;
		nodenew->height = 1;
		return(nodenew);
	}

	if (key < node->key)
		node->left = insert(node->left, key);
	else if (key > node->key)
		node->right = insert(node->right, key);
	else                                                               //ak tam už je
		return(node);
	node->height = 1 + (max(height(node->left), height(node->right)));

	if (((balance(node)) < -1)) {                                       // Right
		if (key > (node->right->key)) {
			return(rotateleft(node));
		}
		if (key < (node->right->key)) {                                // Right Left
			node->right = rotateright(node->right);
			return(rotateleft(node));
		}
	}
	if (((balance(node)) > 1)) {                                        // Left
		if (key < (node->left->key)) {
			return(rotateright(node));
		}
		if (key > (node->left->key)) {                                 // Left Right
			node->left = rotateleft(node->left);
			return(rotateright(node));
		}
	}
	return(node);
}

int search(N0de* node, int key) {
	if (node == NULL) {
		return(0);
	}
	if (node->key == key) {
		return(0);
	}
	if (key < node->key)
		search(node->left, key);
	else if (key > node->key)
		search(node->right, key);
	return(0);
}

int main()
{
    /*
    * clock_t tic = clock();
	N0de* root = NULL;
    struct Node* RBT = NULL;

	for (int i = 0;i < 10000000;i++) {
        root = insert(root, i);
	}
	clock_t toc = clock();
	printf("Inserting  AVL: %f seconds\n", (double)(toc - tic) / CLOCKS_PER_SEC);
	clock_t t1 = clock();
    for (int i = 0;i < 10000000;i++) {
        search(root,i);
    }
	clock_t t2 = clock();
	printf("Search AVL : %f seconds\n", (double)(t2 - t1) / CLOCKS_PER_SEC);
    */
    /*
	clock_t tic = clock();
	N0de* root = NULL;
    struct Node* RBT = NULL;

	for (int i = 0;i < 10000000;i++) {
        RBT = RB_insert(RBT, i);
	}
	clock_t toc = clock();
	printf("Inserting  RB: %f seconds\n", (double)(toc - tic) / CLOCKS_PER_SEC);
	clock_t t1 = clock();
    for (int i = 0;i < 10000000;i++) {
        int x = BST_search(RBT,i);
    }
	clock_t t2 = clock();
	printf("Search RB : %f seconds\n", (double)(t2 - t1) / CLOCKS_PER_SEC);
	
    */
    srand(time(0));



    clock_t tic = clock();
    N0de* root = NULL;
    struct Node* RBT = NULL;

    for (int i = 0;i < 10000000;i++) {
        root = insert(root, (rand() % (10000000 + 1)));
    }
    clock_t toc = clock();
    printf("Inserting  AVL: %f seconds\n", (double)(toc - tic) / CLOCKS_PER_SEC);
    clock_t t1 = clock();
    for (int i = 0;i < 10000000;i++) {
        RBT = RB_insert(RBT, (rand() % (10000000 + 1)));
    }
    clock_t t2 = clock();
    printf("Inserting RB : %f seconds\n", (double)(t2 - t1) / CLOCKS_PER_SEC);


	return(0);
}