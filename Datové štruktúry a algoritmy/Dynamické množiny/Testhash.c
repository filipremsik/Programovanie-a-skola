#define _CRT_SECURE_NO_WARNINGS
#include<stdio.h>
#include<stdlib.h>
#include<string.h>
#include<time.h>
#define count 100000;
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
struct data
{
	int key;
	int value;
};

struct data* array;
int capacity = count;
int size = 0;

 // this function gives a unique hash code to the given key 
int hashcode(int key)                   //
{
	return (key % capacity);
}

// it returns prime number just greater than array capacity 
int get_prime(int n)
{
	if (n % 2 == 0)
	{
		n++;
	}
	for (; !if_prime(n); n += 2);

	return n;
}

// to check if given input (i.e n) is prime or not 
int if_prime(int n)
{
	int i;
	if (n == 1 || n == 0)
	{
		return 0;
	}
	for (i = 2; i < n; i++)
	{
		if (n % i == 0)
		{
			return 0;
		}
	}
	return 1;
}

void init_array()
{
	int i;
	capacity = get_prime(capacity);
	array = (struct data*)malloc(capacity * sizeof(struct data));
	for (i = 0; i < capacity; i++)
	{
		array[i].key = 0;
		array[i].value = 0;
	}
}

// to insert a key in the hash table 
void insert(int key)
{
	int index = hashcode(key);
	if (array[index].value == 0)
	{
		//  key not present, insert it  
		array[index].key = key;
		array[index].value = 1;
		size++;
	}
	else if (array[index].key == key)
	{
		// updating already existing key  
		printf("\n Key (%d) already present, hence updating its value \n", key);
		array[index].value += 1;
	}
	else
	{
		//  key cannot be insert as the index is already containing some other key  
		printf("\n ELEMENT CANNOT BE INSERTED \n");
	}
}


///////////////////////////////////////////////////////////////////////////////////////////////////////
int start = 0;
hash(char*** table, long long int input) {
	int key = 0;
	char word[20];
	int copy = count;
	sprintf(word, "%lld", input);
	if (*table == NULL) {
		(*table) = (char**)calloc(copy, sizeof(char*));
		for (int j = 0;j < copy;j++) {
			(*table)[j] = (char*)calloc(50, sizeof(char));
		}
	}

	for (int i = 1;i <= strlen(word); i++) {
		key += ((word[i]) * (i + 7));
	}
	key = (key * (strlen(word))) % count;
	
	if ((strcmp(word, (*table)[key])) == 0) {               //uz je v tabulke
	}
	else
		if (strlen((*table)[key]) == 0) {                      //je tam volne miesto
			strcpy((*table)[key], word);
		}
		else {
			for (int a = start;a < copy;a++) {                //hladam nahradu
				if (strlen((*table)[a]) == 0) {
					strcpy((*table)[a], word);
					start = a;
					break;
				}
			}
		}
}


void main(){
	char** table = NULL;
	int cyklus = 100000;
	clock_t tic = clock();
	init_array();
	for (int i = 0;i < cyklus;i++) {
		insert(i);
	}
	clock_t toc = clock();
	printf("Insert to copy hash %f seconds \n", (double)(toc - tic) / CLOCKS_PER_SEC);
	
	clock_t t1 = clock();
	for (int i = 0;i < cyklus;i++) {
		hash(&table, i);
	}
	clock_t t2 = clock();

	printf("Insert to my hash %f seconds \n", (double)(t2 - t1) / CLOCKS_PER_SEC);
	
	return(0);
}