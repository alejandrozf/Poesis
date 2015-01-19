(in-package :nlpes)

(load "acentuacion")
					; RIMA

					; Es la igualdad, en una composicion poetica, entre sonidos de 
					; dos o mas palabras desde la ultima vocal acentuada. La rima  
					; puede ser  consonante o perfecta cuando hay igualdad entre 
					; todos los sonidos a partir de la ultima vocal acentuada. Es 
					; asonante o imperfecta si solo hay igualdad de los sonidos vocalicos. 


					;Falta el caso de tres vocales seguidas en una silaba.
(defun EDUVA (silaba)
  (block nil 
    (maplist 
     (lambda (c) 
       (cond ((vocal-acentuada? (first c)) 
	      (return c))
	     ((and (vocal? (first c))
		   (not (vocal? (second c))))
	      (return c))
	     ((and (vocal? (first c))
		   (vocal-acentuada? (second c)))
	      (return (cdr c)))
	     ((and (vocal-abierta-no-acentuada? (first c))
		   (vocal-cerrada-no-acentuada? (second c))
		   (not (vocal? (third c))))
	      (return c))
	     ((and (vocal-cerrada-no-acentuada? (first c))
		   (vocal-abierta-no-acentuada? (second c))
		   (not (vocal? (third c))))
	      (return (cdr c)))
	     ((and (vocal-cerrada-no-acentuada? (first c))
		   (vocal-cerrada-no-acentuada? (second c))
		   (not (vocal? (third c))))
	      (return (cdr c)))	
	     (t 'faltan-casos))) 
     silaba)))


(defun rima-consonante (palabra1 palabra2)
  (let ((l1 (nth-value 1 (acentuacion palabra1)))
	(l2 (nth-value 1 (acentuacion palabra2))))
    (every  (lambda (c d)
	      (or (equalp c d)
		  (equalp (fonema-valor c) 
			  (fonema-simbolo d))
		  (equalp (fonema-valor d) 
			  (fonema-simbolo c))))
	    (apply #'append 
		   (cons (EDUVA (car l1)) 
			 (cdr l1)))
	    (apply #'append 
		   (cons (EDUVA (car l2)) 
			 (cdr l2))))))

(defun rima-asonante (palabra1 palabra2)
  (let ((l1 (nth-value 1 (acentuacion palabra1)))
	(l2 (nth-value 1 (acentuacion palabra2))))
    (every  (lambda (c d)
	      (or (equalp c d)
		  (equalp (fonema-valor c) 
			  (fonema-simbolo d))
		  (equalp (fonema-valor d) 
			  (fonema-simbolo c))))
	    (extraer-vocales (apply #'append 
				    (cons (EDUVA (car l1)) 
					  (cdr l1))))
	    (extraer-vocales (apply #'append 
				    (cons (EDUVA (car l2)) 
					  (cdr l2)))))))

(defun extraer-vocales (seccion-palabra)
  (remove-if-not #'vocal? seccion-palabra))
