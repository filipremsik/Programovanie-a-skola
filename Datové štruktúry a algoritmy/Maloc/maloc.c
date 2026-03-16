#define _CRT_SECURE_NO_WARNINGS
#include <stdio.h>
#include <string.h>
#include <stdlib.h>
#define START (int*)(memory+4)
#define END  (int*)(memory+(*(int*)(memory))-4)
#define NEXT ((char*)(valid_ptr)-4+abs(*((int*)(valid_ptr)-1)))
#define PREV ((int*)(valid_ptr)-2)
#define AKT  *((int*)(valid_ptr)-1)
#define HEB *((int*)((char*)(valid_ptr)-4-*PREV))


char* memory= NULL;

void memory_init(void *region,unsigned int size) {
	memory = region;
	memset(memory,0,size);
	*((int*)(memory)) = size;                     //hlavièka pamäte
	*(((int*)(memory)) + 1) = size-4;	         //zaèiatok ukladacieho priestoru
	*(END) = size-4;							//koniec pamäte + posledná päta
}
void *memory_alloc(unsigned int size) {
	char* look = START;
	int save = (int*)(size);
	if ((size == 0)||(size> (*((int*)(memory)))-12)) {
		return(NULL);
	}
	while((look<END)&&(*((int*)(look))<save+8)){               //prehladavanie do kým nie som na konci alebo nenájdem vo¾né miesto
		look = look + abs(*((int*)(look)));
		}
	

	if (look<END) {						                                            //ak našlo miesto
		if (size+8== *((int*)(look))) {                                            //ak sa potrebná a nájdená pamä zhodujú
			(*((int*)(look + (*((int*)(look))) - 4))) = -(*((int*)(look)));       //zmena päty
			(*((int*)(look))) = -(*((int*)(look)));						          //zmena hlavièky
			return(look+4);

		}


		else if (size + 16 <= *((int*)(look))) {		                                       //ak je dos miesta na ïalšiu h,p
			(*((int*)(look + (*((int*)(look))) - 4))) = (*((int*)(look))) - size - 8;		  //zmena pôvodnej päty 
			(*((int*)(look + size + 8))) = (*((int*)(look))) - size - 8;					 //nová  hlavièka zvyšku
			(*((int*)(look + size + 4))) = -save - 8;									    //nová päta alokovanaj pamäte
			(*((int*)(look))) = -save - 8;											       //zmena pôvodnej hlavièky
			return(look+4);

		}   
		
				                                                                             //ak ostane málo miesta na vytvorenie h,p
		else {
			
			 if ((*((int*)(look)) - size < 16) && (*((int*)(look)) - size >= 12)) {
				int add = *((int*)(look)) - size - 12;
				(*((int*)(look + (*((int*)(look))) - 4))) = 4;		                             //pôvodnej päty 
				(*((int*)(look + (*((int*)(look))) - 8))) = -save - 8 - add;		            //nová päta alokovanaj pamäte
				(*((int*)(look))) = -save - 8-add;											   //zmena pôvodnej hlavièky

				return(look + 4);
			 }
			 if ((*((int*)(look)) - size < 12) && (*((int*)(look)) - size > 8)) {
				(*((int*)(look + (*((int*)(look))) - 4))) = -(*((int*)(look)));         //zmena päty
				(*((int*)(look))) = -(*((int*)(look)));						           //zmena hlavièky
				return(look + 4);
			 }		 
		}

	}
	else {								        //ak nenašlo miesto
		return(NULL);
	}
	printf("\n");
}

int memory_check(void*ptr){
	char* look = START;
	if( START>= ptr || END < ptr||ptr==NULL){
	 return(0);
		}
	while (look < END) {
		if ((ptr>=(look+4))&& (ptr <= (look + abs((*(int*)(look)))-5  ))&&(*(int*)(look))<0) {
			return(1);
		}
		look = look + abs(*((int*)(look)));
	}
	return(0);
}

int memory_free(void* valid_ptr) {
	int new_size;
	if (((PREV == memory)&&(NEXT >= END))||((*PREV<0)&&(*((int*)(NEXT))<0)) || ((PREV == memory) && (*((int*)(NEXT)) < 0))||((*PREV<0) && (NEXT > END))) { //v okolí nie sú prázdne bloky
		*((unsigned int*)(valid_ptr)-1)= abs(*((unsigned int*)(valid_ptr)-1));
		*((int*)(NEXT - 4)) = abs(*((int*)(NEXT - 4)));
		return(0);	


	}else if (((PREV == memory)||(*PREV < 0))&&(*((int*)(NEXT)) > 0)) {     //vpravo je volny blok
		new_size = abs(AKT) + *((int*)NEXT);
		if (*((int*)(NEXT)) == 4) {
			*((int*)(NEXT)) = new_size;
			*((int*)(NEXT - 4)) = 0;
			AKT = new_size;
			return(0);
		}
		*((int*)(NEXT + *(NEXT)-4)) = new_size;
		*((int*)(NEXT)) = 0;
		*((int*)(NEXT-4)) = 0;
		AKT = new_size;		
		return(0);
	}


	else if ((*PREV > 0)&&((NEXT > END) || (*((int*)(NEXT)) < 0))) {   //vlavo je volny blok
		new_size= abs(AKT) + *(PREV);
		if (*PREV == 4) {
			*((int*)(NEXT - 4)) = new_size;
			AKT = 0;
			*PREV = new_size;
			return(0);
		}
		*((int*)(NEXT - 4)) = new_size;
		HEB = new_size;
		AKT = 0;
		*PREV = 0;
		return(0);
	}


	else if ((*PREV > 0)&& (*((int*)(NEXT)) > 0)) {      //obe strany su volne
		new_size= abs(AKT) + *(PREV) + *((int*)NEXT);
		if ((*((int*)(NEXT)) == 4) && (*PREV == 4)) {
			*PREV = new_size;
			*((int*)(NEXT)) = new_size;
			*((int*)(NEXT - 4)) = 0;
			AKT = 0;
			return(0);
		}

		if (*((int*)(NEXT)) == 4) {
			*((int*)(NEXT)) = new_size;
			HEB = new_size;
			*PREV = 0;
			*((int*)(NEXT - 4)) = 0;
			AKT = 0;
			return(0);
		}
		if (*PREV == 4) {
			*PREV = new_size;
			*((int*)(NEXT + *(NEXT)-4)) = new_size;
			*((int*)(NEXT)) = 0;
			*((int*)(NEXT - 4)) = 0;
			AKT = 0;
			return(0);
		}
		
		HEB = new_size;
		*((int*)(NEXT + *(NEXT)-4)) = new_size;
		*PREV = 0;
		*((int*)(NEXT)) = 0;
		*((int*)(NEXT-4)) = 0;
		AKT = 0;
		return(0);
	}
	else {
		return(1);
	}
}
/*
int main() {
	char region[170];
	memory_init(region,170);
	int a;
	char* pointer = (char*)memory_alloc(10);
	if (pointer == NULL) {
		printf("Nepodarilo sa alokovat\n");
	}
	else {
		printf("pointer %d\n", *((int*)(pointer-4)));
	}
	
	char* pointer1 = (char*)memory_alloc(10);
	if (pointer1 == NULL) {
		printf("Nepodarilo sa alokovat\n");
	}
	else {
		printf("pointer %d\n", *((int*)(pointer1 - 4)));
	}
	
	char* pointer2 = (char*)memory_alloc(28);
	if (pointer2 == NULL) {
		printf("Nepodarilo sa alokovat\n");
	}
	else {
		printf("pointer %d\n", *((int*)(pointer2 - 4)));
	}
	a = memory_free(pointer);
	a = memory_check(pointer2);
	printf(" %d",a);
	/*
	a = memory_free(pointer1);
	printf(" %d\n",a);
	a = memory_free(pointer);
	printf(" %d\n", a);
	
	a = memory_free(pointer1);
	char* pointero = (char*)memory_alloc(6);
	a = memory_free(pointer2);
	printf(" %d\n", a);
	
	return(0);
}

*/


int main() {
	char region[200];
	char* pointer[20];
	memory_init(region, 200);
	int range = 200;
	int sum = 0;
	int min = 8;
	int max = 24;
	int a = -1;
	int aloc=0;
	int pamet = 0;
	while (range > sum + min) {
		int num = (rand() % (max - min + 1)) + min;
		if (sum + num <= range) {
			sum = sum + num;
			a++;
			pointer[a] = memory_alloc(num);
			if (pointer[a]) {
				aloc++;
				pamet = pamet + num+8;
			}
		}
	}
	float f = a+1;
	float g = aloc;
	printf("Pamet velkosti %d s blokmi od %d bajtov do %d bajtov.\nVysledky: alokovanych %.2lf %% blokov",range,min,max,(aloc/f)*100);
	return(0);
}

/*
int main() {
	char region[8000];
	char* pointer[200];
	memory_init(region, 8000);
	int range = 8000;
	int sum = 0;
	int min = 500;
	int max = 5000;
	int a = -1;
	int aloc=0;
	int pamet = 0;
	while (range > sum + min) {
		int num = (rand() % (max - min + 1)) + min;
		if (sum + num <= range) {
			sum = sum + num;
			a++;
			pointer[a] = memory_alloc(num);
			if (pointer[a]) {
				aloc++;
				pamet = pamet + num+8;
			}
		}
	}
	float f = a+1;
	float g = aloc;
	printf("Pamet velkosti %d s blokmi od %d bajtov do %d bajtov.\nVysledky: alokovanych %.2lf %% blokov",range,min,max,(aloc/f)*100);
	return(0);
}
*/
/*
int main() {
	char region[55000];
	char* pointer[200];
	memory_init(region, 55000);
	int range = 55000;
	int sum = 0;
	int min = 8;
	int max = 5000;
	int a = -1;
	int aloc=0;
	int pamet = 0;
	while (range > sum + min) {
		int num = (rand() % (max - min + 1)) + min;
		if (sum + num <= range) {
			sum = sum + num;
			a++;
			pointer[a] = memory_alloc(num);
			if (pointer[a]) {
				aloc++;
				pamet = pamet + num+8;
			}
		}
	}
	printf("%d\n",a);
	float f = a+1;
	float g = aloc;
	printf("Pamet velkosti %d s blokmi od %d bajtov do %d bajtov.\nVysledky: alokovanych %.2lf %% blokov",range,min,max,(aloc/f)*100);
	return(0);
}
*/



