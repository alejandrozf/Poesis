(load "acentuacion")

;;Testear todo el modulo

(defun silabas-metricas->pprint (lista-palabras)
	(mapcar (lambda (c) 
				(reduce (lambda (x y) (concatenate 'string x (fonema-valor y))) c 
				:initial-value "")) 
			(silabas-metricas lista-palabras)))

;;revisar bien las sigs 3 funciones
(defun silabas-metricas (lista-palabras)
	(reduce (lambda (c d) 
				(if (puente? c d) 
					(append-to-last c d) 
					(append-to-list c d)))
			(mapcan #'silabas->lista lista-palabras)))
;;Para concatenar un valor al ultimo valor de 
;;una lista.  
(defun append-to-last (c d)
	(cond ((simple-list? c) (append c d))
		  ((t (append (butlast c) (car (last c)) d)))))	

(defun append-to-list (c d)
	(cond ((simple-list? c) (append (list c) (list d)))
		  (t (append c (list d)))))		
			
;;ver el caso de si hay h intermedia
(defun puente? (silaba1 silaba2)
	(and (vocal? (car (last silaba1))) 
		 (vocal? (car silaba2))))
		 
;;Si no es una lista de listas 
;;(o sea, si es el primer elemento del reduce)
(defun simple-list? (c)
	(atom (car c)))

;;tratar de integrarla con 'silabas-metricas
(defun numero-silabas-metricas (lista-palabras)
	(+ (length (silabas-metricas lista-palabras)) 
	   (id (car (last lista-palabras)))))

(defun id (palabra)
	(case (acentuacion palabra) 
		((LLANA) 0)
		((AGUDA) 1)
		(t -1)))


