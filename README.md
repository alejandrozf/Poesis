Esta es una colecci�n de algunas utilidades para hacer NPL 
a palabras en espa�ol(castellano).


Actualmente brinda las siguientes opciones:

1.- Dividir en s�labas una palabra
Ejemplo: 
NLPES> (silabas->cadena "programador")
"PRO-GRA-MA-DOR"

2.- Determinador el tipo de acentuaci�n de una palabra
Ejemplos:
NLPES> NLPES> (acentuacion "implementaci�n")
AGUDA
((#S(FONEMA :VALOR "C" :SIMBOLO "S") #S(FONEMA :VALOR "I" :SIMBOLO NIL)
  #S(FONEMA :VALOR "�" :SIMBOLO NIL) #S(FONEMA :VALOR "N" :SIMBOLO NIL)))
NLPES> (acentuacion "tecnolog�a")
LLANA
((#S(FONEMA :VALOR "G" :SIMBOLO "J") #S(FONEMA :VALOR "�" :SIMBOLO NIL))
 (#S(FONEMA :VALOR "A" :SIMBOLO NIL)))
NLPES> (acentuacion "br�jula")
ESDRUJULA
((#S(FONEMA :VALOR "BR" :SIMBOLO NIL) #S(FONEMA :VALOR "�" :SIMBOLO NIL))
 (#S(FONEMA :VALOR "J" :SIMBOLO NIL) #S(FONEMA :VALOR "U" :SIMBOLO NIL))
 (#S(FONEMA :VALOR "L" :SIMBOLO NIL) #S(FONEMA :VALOR "A" :SIMBOLO NIL)))

3.- Determinar si dos palabras riman (de forma asonante y/o consonante).
NLPES> (rima-consonante "mazazo" "ocaso")
T
NLPES> (rima-asonante "volaron" "pegados")
T

4.- Determinar las silabas m�tricas de una oraci�n (o verso)
NLPES> (silabas-metricas->pprint '("Los" "programas" "y" "los" "poemas"))
("LOS" "PRO" "GRA" "MAS" "Y" "LOS" "POE" "MAS")

