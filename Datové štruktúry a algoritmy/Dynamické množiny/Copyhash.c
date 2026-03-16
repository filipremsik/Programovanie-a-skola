#define _CRT_SECURE_NO_WARNINGS
#include<stdio.h>
#include<stdlib.h>
#define count 100;
struct data
{
	int key;
	int value;
};

struct data* array;
int capacity = count;
int size = 0;

/* this function gives a unique hash code to the given key */
int hashcode(int key)
{
	return (key % capacity);
}

/* it returns prime number just greater than array capacity */
int get_prime(int n)
{
	if (n % 2 == 0)
	{
		n++;
	}
	for (; !if_prime(n); n += 2);

	return n;
}

/* to check if given input (i.e n) is prime or not */
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

/* to insert a key in the hash table */
void insert(int key)
{
	int index = hashcode(key);
	if (array[index].value == 0)
	{
		/*  key not present, insert it  */
		array[index].key = key;
		array[index].value = 1;
		size++;
	}
	else if (array[index].key == key)
	{
		/*  updating already existing key  */
		printf("\n Key (%d) already present, hence updating its value \n", key);
		array[index].value += 1;
	}
	else
	{
		/*  key cannot be insert as the index is already containing some other key  */
		printf("\n ELEMENT CANNOT BE INSERTED \n");
	}
}

/* to display all the elements of a hash table */
void display(int key)
{
	key = key % capacity;
	for (int i = 0; i < capacity; i++)
	{
		if (array[i].key == key)
		{
			break;
		}
	}
}


void main()
{
	int choice, key, value, n, c;

	init_array();
	for (int i = 0;i < 100;i++) {
		insert(i);
	}
	display(9);
	return(0);
}