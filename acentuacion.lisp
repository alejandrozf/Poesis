;;CLASIFICAR LA PALABRA DE ACUERDO A LA FUERZA DE PRONUNCIACION
(load "silabas")

;;Esta funcion devuelve la clasificacion de la palabra
;;y ademas la ruta desde la vocal acentuada hasta el final de la palabra.
(defun acentuacion (palabra)
	(let* ((lista-silabas (silabas->lista palabra))
		   (n (length lista-silabas)))
		(loop for i 
			  from 0
			  for silaba in	lista-silabas
			  if (and (tilde? silaba) 
					  (< i (- n 2))) 
				return (values 'ESDRUJULA 
							   (nthcdr i lista-silabas))
			  if (>= i (- n 2)) 
				collect silaba
			  into result
			  finally 
			  (return (let* ((m (length result))
							 (penultima-silaba 
								(and (= m 2) (first result)))
							 (ultima-silaba (car (last result))))
						(cond ((tilde? ultima-silaba) 
								(values 'AGUDA (list ultima-silaba)))
							  ((tilde? penultima-silaba)
								(values 'LLANA result))
							  ((and (> m 1) (n-s-vocal? ultima-silaba)) 
								(values 'LLANA result))
							  (t (values 'AGUDA (list ultima-silaba)))))))))
						

(defun n-s-vocal? (silaba)
	(let ((ultimo-caracter (car (last silaba))))
		(or (equal (fonema-valor ultimo-caracter) "N")
			(equal (fonema-valor ultimo-caracter) "S")
			(vocal? ultimo-caracter))))
						  
					  
(defun tilde? (silaba)
	(some #'vocal-acentuada? silaba))
