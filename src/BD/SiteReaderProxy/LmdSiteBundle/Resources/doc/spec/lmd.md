# Specs for monde-diplomatique.fr

## Detecting logged in/logged out
If logged out, a#slaide has href="/connexion".
If logged in, a#slaide has href="/mon_compte".

## Login procedure

### Get the login form
```
GET http://www.monde-diplomatique.fr/
```

The form is named form#identification_lecteur.

```html
<form action="https://lecteurs.mondediplo.net?page=connexion" method="post" id="identification_lecteur">
  <div>
    <input name="page" value="connexion" type="hidden">
    <input name="formulaire_action" type="hidden" value="identification_lecteur">
    <input name="formulaire_action_args" type="hidden" value="ECZsXQrtXOu2KjdrPiUDZdNCiC+q+NJ/mLLmDo+CRwO3BWcAoywwM5Bcy5Gp2HRC8dWljr0/Q2pOB5f3Xp6FYj1bx7Iz0os=">
    <input name="retour" value="http://www.monde-diplomatique.fr/" type="hidden">
    <input name="site_distant" value="http://www.monde-diplomatique.fr/" type="hidden">
  </div>
  <ul>
    <li>
      <label>E-mail&nbsp;:</label>
      <input type="text" name="email" value="" autocapitalize="off">
    </li>
    <li>
      <label>Mot de passe&nbsp;:</label>
      <input type="password" class="password " name="mot_de_passe" value="">
    </li>
    <li class="editer_session">
      <div class="choix">
        <input type="checkbox" class="checkbox" name="session_remember" id="session_remember" value="oui" onchange="jQuery(this).addClass('modifie');">
        <label class="nofx" for="session_remember">Rester identifié quelques jours</label>
      </div>
    </li>
    <li class="submit">
      <input type="submit" name="valider" title="S'identifier" value="valider">
    </li>
    <li class="dernier">
      <a title="Demander un nouveau mot de passe par courriel." href="https://boutique.monde-diplomatique.fr/customer/account/forgotpassword/" class="oubli">Mot de passe oublié ?</a>
    </li>
  </ul>
</form>
```

## Post the login form
```
POST https://lecteurs.mondediplo.net/?page=connexion

page: connexion
formulaire_action: identification_lecteur
formulaire_action_args: SomeLargeHash
retour: http://www.monde-diplomatique.fr/
site_distant: http://www.monde-diplomatique.fr/
email: email@example.com
mot_de_passe: abcdefgh
valider: valider
```

Follow the Location header (http://www.monde-diplomatique.fr/?action=login_lecteur&email=...)
Eat the `spip_session` Cookie.

