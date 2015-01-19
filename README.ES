Esta es una colección de algunas utilidades para hacer NPL 
a palabras en español(castellano).


Actualmente brinda las siguientes opciones:

1.- Dividir en sílabas una palabra
Ejemplo: 
NLPES> (silabas->cadena "programador")
"PRO-GRA-MA-DOR"

2.- Determinador el tipo de acentuación de una palabra
Ejemplos:
NLPES> NLPES> (acentuacion "implementación")
AGUDA
((#S(FONEMA :VALOR "C" :SIMBOLO "S") #S(FONEMA :VALOR "I" :SIMBOLO NIL)
  #S(FONEMA :VALOR "Ó" :SIMBOLO NIL) #S(FONEMA :VALOR "N" :SIMBOLO NIL)))
NLPES> (acentuacion "tecnología")
LLANA
((#S(FONEMA :VALOR "G" :SIMBOLO "J") #S(FONEMA :VALOR "Í" :SIMBOLO NIL))
 (#S(FONEMA :VALOR "A" :SIMBOLO NIL)))
NLPES> (acentuacion "brújula")
ESDRUJULA
((#S(FONEMA :VALOR "BR" :SIMBOLO NIL) #S(FONEMA :VALOR "Ú" :SIMBOLO NIL))
 (#S(FONEMA :VALOR "J" :SIMBOLO NIL) #S(FONEMA :VALOR "U" :SIMBOLO NIL))
 (#S(FONEMA :VALOR "L" :SIMBOLO NIL) #S(FONEMA :VALOR "A" :SIMBOLO NIL)))

3.- Determinar si dos palabras riman (de forma asonante y/o consonante).
NLPES> (rima-consonante "mazazo" "ocaso")
T
NLPES> (rima-asonante "volaron" "pegados")
T

4.- Determinar las silabas métricas de una oración (o verso)
NLPES> (silabas-metricas->pprint '("Los" "programas" "y" "los" "poemas"))
("LOS" "PRO" "GRA" "MAS" "Y" "LOS" "POE" "MAS")

