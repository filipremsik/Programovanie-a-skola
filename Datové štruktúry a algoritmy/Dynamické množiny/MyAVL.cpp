#define _CRT_SECURE_NO_WARNINGS
#include<stdio.h>
#include<stdlib.h>
#include <time.h>


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
	clock_t tic = clock();
	N0de* root = NULL;

	for (int i = 0;i < 1;i++) {
		root = insert(root, i);
	}

	clock_t toc = clock();
	printf("Inserting: %f seconds\n", (double)(toc - tic) / CLOCKS_PER_SEC);


	tic = clock();

	for (long long int i = 1; i <= 1; i++) {

	}

	toc = clock();
	printf("Searching: %f seconds\n", (double)(toc - tic) / CLOCKS_PER_SEC);
	printf("%d\n", root->height);



	return(0);
}