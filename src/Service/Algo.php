<?php

namespace App\Service;

class Algo
{
    public function generateDevis(int $nb_menage, int $duree_contrat, int $localite,int $nb_champ)
    {
        //organisme,email,numero,localite,distance,nbrmenage,ptkioske,contractduration
        
        //$nb_menage = 2; // À saisir
        //$Nb_champ = 1; // Par Valentin

        // Variables de maintenance
       // $duree_contrat = 5; // À saisir
//?
       // $localite = 15; // À saisir

//?
        $consommable = 1; // À définir par Jacquis

        // Installation du générateur


        $cout_installation_unitaire = 10000; // Ar par Aina
        $main_d_oeuvre = 73500; // Ar (défini par Rina, 200 000 Ar)
       // $nb_champ = 1; // 1 par Rina
        $nb_kiosque = $nb_champ; // À saisir
        $consommation_unitaire = 500; // Par Valentin (par semaine ou jour)
        $engrais = 10000; // Défini par Rina
        $semence = 10000; // Défini par Rina

        // Densité énergétique
        $densite_energetique = 0.4;

        $CUMP = 367500; // Défini par la finance

        // Estimation de la consommation unitaire = moyenne de la consommation par ménage

        // Nombre de ménages, offre de production d'énergie ???

        function calcul_frais_deplacement($l) 
        {
            $fd = 0;
            if ($l < 15) {
                $fd = 7500;
            } 
            elseif ($l < 30) {
                $fd = 150000;
            }         
            elseif ($l < 45) {
                $fd = 225000;
            } 
            elseif ($l < 60) {
                $fd = 300000;
            } else 
            {
                $fd = 375000;
            }
            return $fd;
        }

// Calcul du devis
        //function calcul_devis() 
        //{
            #global $localite, $duree_contrat, $consommable, $nb_kiosque, $cout_installation_unitaire, $CUMP, $consommation_unitaire, $engrais, $semence, $densite_energetique, $main_d_oeuvre, $nb_menage;

            $frais_deplacement = calcul_frais_deplacement($localite);

            // Calcul de maintenance
            $frequence = $duree_contrat * 0.5;
            $maintenance = ($frais_deplacement + $consommable) * $frequence;

            // Calcul de l'installation du générateur
            $cout_installation_kiosque = $nb_kiosque * $cout_installation_unitaire;
            $frais_deplacement = calcul_frais_deplacement($localite);
            $offre_production_energie = $nb_menage * $consommation_unitaire + ($consommation_unitaire * 0.1);
            $surface_totale_champ = $offre_production_energie / $densite_energetique;
            $champ_culture = $surface_totale_champ + $engrais + $semence + $main_d_oeuvre;
            $installation_generateur = $cout_installation_kiosque + $champ_culture + $frais_deplacement;

            // Calcul du coût total des kits
            $cout_total_kits = $CUMP * $nb_menage;

            // Calcul du coût opérationnel
            $cout_operationnel = $cout_total_kits + $installation_generateur + $maintenance;

            // Calcul du devis
            $devis_prestation_service = $cout_operationnel + $cout_operationnel * 0.25;

            return round($devis_prestation_service);
        //}*/

        //return  $offre_production_energie ;
    }
    
}