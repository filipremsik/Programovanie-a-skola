#define _CRT_SECURE_NO_WARNINGS
#include<stdio.h>
#include<stdlib.h>
#include<string.h>
#include<time.h>
#define count 100000;
int start = 0;
 hash(char ***table,long long int input) {
	int key = 0;
	char word[20];
	int copy = count;
	sprintf(word,"%lld",input);
	if (*table==NULL) {
		(*table) = (char**)calloc(copy, sizeof(char*));
		for (int j = 0;j < copy;j++) {
			(*table)[j] = (char*)calloc(50, sizeof(char));
		}
	}
	
	for (int i = 1;i <= strlen(word); i++) {
		key += ((word[i]) * (i + 1));
	}
	key = (key*(strlen(word))) % count;
	if ((strcmp(word, (*table)[key])) == 0) {               //uz je v tabulke
	}
	else 
	if(strlen((*table)[key])==0){                      //je tam volne miesto
		strcpy((*table)[key], word);
	}
	else {

		for (int a = start;a < copy;a++) {
			if (strlen((*table)[a]) == 0) {
				strcpy((*table)[a], word);
				start = a;

				break;
			}
		}
	}	
}
int main() {
	char** table = NULL;
	
	for (int i = 0;i < 99999;i++) {
		hash(&table, i);
	}

}
