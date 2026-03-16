#define _CRT_SECURE_NO_WARNINGS
#include <stdio.h>
#include <string.h>
#include <math.h>
#include <time.h>
typedef struct NODE {
	char output;
	struct NODE* right;
	struct NODE* left;
}NODE;
typedef struct BDD {
	int count;
	int nodes;
	 NODE* root;
 }BDD;
NODE* new_node(char* c,NODE *node) {
	node = (NODE*)malloc(sizeof(NODE));
	node->output =-'2';
	if ((strlen(c)) > 1) {
		int len = (strlen(c) / 2);
		char* d = malloc(len + 1);
		char* e = malloc(len + 1);
		memcpy(d, c, len);
		memcpy(e, c + len, len);
		d[len] = '\0';
		e[len] = '\0';
		node->left=new_node(d,node->left);
		node->right=new_node(e,node->right);
		free(d);
		free(e);
		return(node);
	}
	if ((strlen(c)) == 1) {
		node->left = NULL;
		node->right = NULL;
		node->output =c[0];
		return(node);

	}


}
BDD* BDD_create(char *c,BDD *new){
	if ((int)(log10(strlen(c)) / log10(2)) - (log10(strlen(c)) / log10(2)) != 0) {
		return(NULL);
	}
	if (new == NULL) {
		new = (BDD*)malloc(sizeof(BDD));
	}
	new->count = ((int)(log10(strlen(c)) / log10(2)));
	new->nodes = ((int)strlen(c))-1;
	new->root = new_node(c,new->root);
	return(new);
}
char BDD_use(BDD* bdd, char* vstupy) {
	NODE* node = bdd->root;
	if (strlen(vstupy) != bdd->count) {
		return(-'1');
	}
	for (int i = 0;i <= strlen(vstupy);i++) {
		if (vstupy[i]=='0') {
			node = node->left;

		}
		if (vstupy[i]=='1') {
			node = node->right;

		}
	}
	return(node->output);
}
/*
int main() {
	BDD* bdd=NULL;
	char c;
	bdd=BDD_create("abcdefgh",bdd);
	if (bdd == NULL) {
		printf("Zly vstup");
		exit(0);
	}
	printf("Premena %d\n",bdd->count);
	printf("Uzol %d\n", bdd->nodes);
	c = BDD_use(bdd,"111");
	if (c < 0) {
		printf("chyba");
	}
	else {
		printf("%c",c);
	}
}
*/
void BDD_free(NODE* root) {
	if (root->left != NULL) {
		BDD_free(root->left);
		BDD_free(root->right);
	}
	free(root);

}
int main() {
	BDD* bdd;
	char* pole,*use;
	double timer=0;
	char c;
	int premenne = 13;
	int dlzka = 1;
	for (int i = 0;i < premenne;i++) {
		dlzka *= 2;
	}
	dlzka--;
	srand(time(0));
	pole = (char*)calloc(dlzka+2, sizeof(char));
	use= (char*)calloc(premenne+1, sizeof(char));
	for (int j = 0;j < 2000;j++) {
		clock_t tic = clock();
		bdd = NULL;
		for (int i = 0;i <= dlzka;i++) {
			pole[i] = (((char)(rand()%2))+'0');
		}
		
		bdd= BDD_create(pole, bdd);
		if (bdd == NULL) {
			printf("Zly vstup");
	
			exit(0);
		}
		
		for (int k = 0;k <= dlzka + 1;k++) {
			int a = k;
			int len = premenne - 1;
			for (int i = 0;i < premenne;i++) {

				use[i] = '0';
			}
			while (a > 0) {
				use[len] = ((char)(a % 2)) + '0';
				len--;
				a = a / 2;

			}
			
			c = BDD_use(bdd,use);
			if (c < 0) {
				printf("chyba\n");
			}
		}    
		

		

		clock_t toc = clock();
		timer += (double)(toc - tic) / CLOCKS_PER_SEC;
		BDD_free(bdd->root);
		free(bdd);
	}

	
	printf("Create trees %.2f\n",timer);

	free(pole);
}