
let _ =

  (* Ouverture un flot de caractère ; ici à partir de l'entrée standard stdin ; fichier test -> remplacer stdin par  (open_in "test.txt")*)  
  let source = Lexing.from_channel stdin in

  let vin_att, vin = Ast.R.from_file "vin.csv" '|' in
  let viticulteur_att, viticulteur = Ast.R.from_file "viticulteur.csv" '|' in
  let client_att, client = Ast.R.from_file "client.csv" '|' in
  let commande_att, commande = Ast.R.from_file "commande.csv" '|' in

  let env = Env.empty in
  let env = Env.add "vin" (vin, vin_att) env in
  let env = Env.add "viticulteur" (viticulteur, viticulteur_att) env in
  let env = Env.add "client" (client, client_att) env in
  let env = Env.add "commande" (commande, commande_att) env in


  (*Fonction pour inverser l'ordre de 'result' sinon provoque une erreur de compilation*)
  let rec inverse env = (match env with
                            |[]-> []
                            | x :: next -> (match x with
                                            | (s,rAttribut) -> (rAttribut,s) :: (inverse next)
                                            )
                        ) in

  (* Boucle infinie interompue par une exception correspondant à la fin de fichier *)
  let rec f () =
    try
      Printf.printf "\n"; flush stdout;
      (* Récupération d'une expression à partir de la source puis affichage de l'évaluation *)
      let q = Parser.ansyn Lexer.anlex source in

      Printf.printf "%s\n\n" (Ast.string_of_query q); flush stdout;
      let result = Ast.eval_query env q in 
            (match result with
              | (relation,old_env) ->  Ast.R.print '|' (inverse old_env) relation); flush stdout;

      f ()
    with Lexer.Eof -> Printf.printf "Bye\n"
  in

  f ()

