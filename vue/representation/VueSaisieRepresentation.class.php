<?php

namespace vue\representation;

use vue\VueGenerique;
use modele\dao\GroupeDAO;
use modele\dao\LieuDAO;
use modele\dao\Bdd;
use modele\metier\Representation;
use modele\metier\Groupe;
use modele\metier\Lieu;
/**
 * Description of VueSaisieGroupe
 *
 * @author hcaillaud
 */
class VueSaisieRepresentation extends VueGenerique {

    /** @var Representation representation à afficher */
    private $uneRepresentation;

    /** @var array Lieu tous les lieux */
    private $lesLieux;

    /** @var array Groupe tous les groupes*/
    private $lesGroupes;

    /** @var string ="creer" ou = "modifier" en fonction de l'utilisation du formulaire */
    private $actionRecue;

    /** @var string ="validerCreer" ou = "validerModifier" en fonction de l'utilisation du formulaire */
    private $actionAEnvoyer;

    /** @var string à afficher en tête du tableau */
    private $message;

    public function __construct() {
        parent::__construct();
    }
    public function afficher() {
        include $this->getEntete();?>
        <form method="POST" onsubmit="return verifForm()" action="index.php?controleur=representations&action=<?= $this->actionAEnvoyer ?>">
            <br>
            <table width="85%" cellspacing="0" cellpadding="0" class="tabNonQuadrille">

                <tr class="enTeteTabNonQuad">
                    <td colspan="3"><strong><?= $this->message ?></strong></td>
                </tr>

                <?php
                // En cas de création, l'id est accessible à la saisie
                if ($this->actionRecue == "creer") {
                    // On a le souci de ré-afficher l'id tel qu'il a été saisi
                    ?>
                    <tr class="ligneTabNonQuad">
                        <td> Id*: </td>
                        <td><input type="text" value="<?= $this->uneRepresentation->getId() ?>" name="id" id="id" onblur="verifId('id')" size ="10" maxlength="8" placeholder="0000R"><div id="err_id" style="color:red;"></div></td>
                    </tr>
                    <?php
                } else {
                    // sinon l'id est dans un champ caché
                    ?>
                    <tr>
                        <td><input type="hidden" value="<?= $this->uneRepresentation->getId(); ?>" name="id"></td><td></td>
                    </tr>
                    <?php
                }
                ?>
                <tr class="ligneTabNonQuad">
                    <td> Lieu*: </td>
                <td><select name="lieu" id="lieu">
                        <option selected disabled value="defaut" >Choisir un lieu</option>

                        <?php
                        foreach($this->lesLieux as $unLieu){
                            if($unLieu->getId() == $this->uneRepresentation->getIdLieu()->getId()){
                                echo '<option selected value="'.$unLieu->getId().'">'.$unLieu->getNom().'</option>';
                            }else {
                                echo '<option value="'.$unLieu->getId().'">'.$unLieu->getNom().'</option>';
                            }
                        }
                        ?>
                    </select></td>
                </tr>
                <tr class="ligneTabNonQuad">
                    <td> Groupe*: </td>
                    <td><select name="groupe" id="groupe">
                        <option selected disabled value="defaut" >Choisir un groupe</option>
                        <?php
                        foreach($this->lesGroupes as $unGroupe){
                            if($unGroupe->getId() == $this->uneRepresentation->getIdGroupe()->getId()){
                                echo '<option selected value="'.$unGroupe->getId().'">'.$unGroupe->getNom().'</option>';
                            }else{
                                echo '<option value="'.$unGroupe->getId().'">'.$unGroupe->getNom().'</option>';
                            }
                        }
                        ?>
                    </select></td>
                </tr>
                <tr class="ligneTabNonQuad">
                    <td> Date*: </td>
                    <td><input type="date" id="date" name="date" size="50" maxlength="45" value="<?= $this->uneRepresentation->getDate() ?>"></td>
                </tr>
                <script type="text/javascript">
                    function verifId(champ){
                        var ok = true;
                        var regex = new RegExp("[0-9][0-9][0-9][0-9]R");
                        if(!regex.test(document.getElementById(champ).value)){
                            document.getElementById(champ).value = ""
                            document.getElementById('err_' + champ).innerHTML = "Format d'id non respecté";
                            ok = false;
                        }else{
                            document.getElementById('err_' + champ).innerHTML = "";
                            ok = true;
                        }
                        return ok;
                    }
                    function verifHeure(champ){
                        var ok = true;
                        regex = /^(0[0-9]|1[0-9]|2[0-3]):([0-5][0-9]):([0-5][0-9])$/;
                        if(!regex.test(document.getElementById(champ).value)){
                            document.getElementById(champ).value = "";
                            document.getElementById('err_' + champ).innerHTML = "Format d'heure non respecté";
                            ok = false;
                        }else{
                            document.getElementById('err_' + champ).innerHTML = "";
                            ok = true;
                        }
                        return ok;
                    }
                    function verifList(champ){
                        if(document.getElementById(champ).value === "defaut"){
                            ok = false;
                        }else{
                            ok = true;
                        }
                        return ok;
                    }
                    function verifForm(){
                        var ok = true;
                        if(verifList('groupe') && verifList('lieu') && verifHeure('heureDebut') && verifHeure('heureFin') && verifId('id') && document.getElementById('date').value != ""){
                            ok = true;
                        }else{
                            alert('Le formulaire doit être complet et correct');
                            ok = false;
                        }
                        return ok;
                    }
                </script>
                <tr class="ligneTabNonQuad">
                    <td> Heure début*: </td>
                    <td><input type="text" value="<?= $this->uneRepresentation->getHeureDebut() ?>" name="heureDebut" id="heureDebut" placeholder="00:00:00"
                               size="50" maxlength="45" onblur="verifHeure('heureDebut')"><div id="err_heureDebut" style="color:red;"></div></td>
                </tr>
                <tr class="ligneTabNonQuad">
                    <td> Heure de fin*: </td>
                    <td><input type="text" value="<?= $this->uneRepresentation->getHeureFin() ?>" name="heureFin" id="heureFin" size="50" placeholder="00:00:00"
                               maxlength="35" onblur="verifHeure('heureFin')"><div id="err_heureFin" style="color:red;"></div></td>
                </tr>
            </table>

            <table align="center" cellspacing="15" cellpadding="0">
                <tr>
                    <td align="right"><input type="submit" value="Valider" name="valider">
                    </td>
                    <td align="left"><input type="reset" value="Annuler" name="annuler">
                    </td>
                </tr>
            </table>
            <a href="index.php?controleur=representations&action=consulter">Retour</a>
        </form>
        <?php
        include $this->getPied();
    }

    public function setUneRepresentation(Representation $Representation) {
        $this->uneRepresentation = $Representation;
    }

    public function setLesLieux($lesLieux) {
        $this->lesLieux = $lesLieux;
    }

    public function setLesGroupes($lesGroupes) {
        $this->lesGroupes = $lesGroupes;
    }

    public function setActionRecue(string $action) {
        $this->actionRecue = $action;
    }

    public function setActionAEnvoyer(string $action) {
        $this->actionAEnvoyer = $action;
    }

    public function setMessage(string $message) {
        $this->message = $message;
    }
}
