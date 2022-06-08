
const personnesModule = (function (jQuery, Pagination) {
  "use strict";

  const API_PATH = '../../../services/';
  const API_GETPERSONNES = API_PATH + 'getPersonnes.php'
  const API_GETPERSONNE = API_PATH + 'getPersonne.php'
  const NB_LIGNES_MAX = 5;

  let modaleDOMTarget = null; 
  let filterTargets = null;
  let fieldTarget = null;
  let libelleTarget = null;
  let tbodyTarget = null;
  let barnavTarget = null;
  let filterModaleTarget = null;

  /**
   * Initialisation du module
   * @param {*} refModale 
   * @param {*} refMetier 
   * @returns 
   */
  function init(refModale, refMetier) {
    modaleDOMTarget = document.getElementById(refModale);
    if (!modaleDOMTarget) {
      console.error('Noeud non trouvée dans le DOM : '+refModale);
      return;
    }
    fieldTarget = document.getElementById(refMetier);
    if (!fieldTarget) {
      console.error('Noeud non trouvé dans le DOM : '+refMetier);
      return;
    }
    filterTargets  = modaleDOMTarget.querySelectorAll('[data-filter]')
    if (!filterTargets) {
      console.error('Noeud non trouvée dans le DOM : [data-filter]');
      return;
    }
    let targetsToMove = document.querySelectorAll('[data-hook='+refMetier+']')
    if (!targetsToMove || targetsToMove.length == 0) {
      console.error('Noeuds non trouvés dans le DOM pour l\'attribut [data-hook='+refMetier+'] ');
      return;
    }
    let tbodydomkey = `[data-formlink=${refMetier}]`;
    tbodyTarget = modaleDOMTarget.querySelector(tbodydomkey);
    if (!tbodyTarget) {
        console.error('Elément non trouvé dans le DOM : '+ tbodydomkey);
        return;
    } else {
      tbodyTarget.addEventListener('click', evt=>{
        evt.preventDefault();
        let currentTarget = evt.target;
        if (currentTarget.hasAttribute('data-click')) {
          modaleSelectEntity(currentTarget);
        }
      })
    }
    libelleTarget = document.getElementById('libelle_'+refMetier);
    if (!libelleTarget) {
      console.error('Elément non trouvé dans le DOM : libelle_ '+ refMetier);
      return;
    }

    filterModaleTarget = modaleDOMTarget.querySelector('#filter_'+refMetier);
    if (!filterModaleTarget) {
      console.error('Elément non trouvé dans le DOM : filter_'+refMetier);
      return;
    } else {
      filterModaleTarget.addEventListener('change', evt=>{
        evt.preventDefault();
        HttpFetchAll(evt.target.value);
      })
    }

    barnavTarget = modaleDOMTarget.querySelector('[data-barnav='+refMetier+']')
    if (!barnavTarget) {
      console.error('Elément non trouvé dans le DOM : [data-barnav='+refMetier+']');
      return;
    } else {
      barnavTarget.addEventListener('click', evt=>{
        evt.preventDefault();
        let currentTarget = evt.target;
        let offset = null;

        if (currentTarget.hasAttribute('data-href')) {
          offset = currentTarget.parentNode.getAttribute('data-offset');
          console.log(offset)
          if (offset == 'prev' || offset == 'next') {
            let parent = currentTarget.parentNode.parentNode;
            let tmpoffset = Number(parent.getAttribute('data-offset'));
            let tmptotal = Number(parent.getAttribute('data-total'));
            console.log(tmpoffset)
            if (offset == 'prev') {
              tmpoffset -= NB_LIGNES_MAX;
              if (tmpoffset <= 0) {
                tmpoffset = 1;
              }
            } else {
              tmpoffset += NB_LIGNES_MAX;
              if (tmpoffset > tmptotal) {
                tmpoffset = tmptotal;
              }
            }
            console.log(tmpoffset)
            offset = tmpoffset;
          }
        }

        if (offset != null) {
          HttpFetchAll(filterModaleTarget.value, offset);
        }
      })
    }

    moveItemsOnPage(fieldTarget, targetsToMove);
    if (String(fieldTarget.value).trim() != '' ) {
      HttpFetchOne(fieldTarget.value);
    }

    HttpFetchAll();
  }

  /**
   * Génération de la barre de pagination de la fenêtre modale
   * @param {*} count_rows 
   * @param {*} current_offset 
   * @param {*} nb_by_page 
   */
  function pagenavGenerator(count_rows, current_offset, nb_by_page) {
    var barre_pagination = new Pagination(count_rows, current_offset, nb_by_page, '', {}, true, "pagination");
    barnavTarget.innerHTML = barre_pagination.render();
  }

  /**
   * Sélection d'une ligne dans la liste proposée par la fenêtre modale
   * @param {*} node 
   */
  function modaleSelectEntity(node) {
      let datas = {};
      datas.id = node.getAttribute('data-click');
      let parent = node.parentNode.parentNode;
      datas.name = parent.getAttribute('data-filter');

      fieldTarget.value = String(datas.id).replace('id_', '');
      fieldTarget.setAttribute('data-infos', JSON.stringify(datas));
      libelleTarget.value = node.getAttribute('data-libelle');

      jQuery(modaleDOMTarget).modal('hide');
  }

  /**
   * Supprime les enfants d'un noeud
   * @param {*} node 
   */
   function dropChildren(node) {
    while (node.firstChild) {
        node.removeChild(node.firstChild);
    }
  }

  /**
   * Insère un noeud dans le DOM après un noeud de référence
   * @param {*} newNode 
   * @param {*} referenceNode 
   */
  function insertAfter(newNode, referenceNode) {
    referenceNode.parentNode.insertBefore(newNode, referenceNode.nextSibling);
  }

  /**
   * Déplace des éléments du DOM pour "habiller" le champ de saisie d'une personne
   * en lui ajoutant un bouton de recherche (fenêtre modale) et un champ d'affichage de libellé
   * le champ de saisie lui-même est forcé en lecture seule car une fois la fenêtre modale en place,
   * il ne peut être modifié qu'au travers de cette fenêtre
   * @param {*} domTarget 
   * @param {*} targetsToMove 
   */
  function moveItemsOnPage(domTarget, targetsToMove) {
    // le champ de formulaire passe en "lecture seule"
    domTarget.setAttribute('readonly', 'readonly');
    // déplacement des éléments du DOM qui vont enrichir le formulaire (bouton de recherche et champ libellé)
    for (let i=0, imax=targetsToMove.length; i<imax; i++) {
      insertAfter(targetsToMove[i], domTarget);
    }
  }

  /**
   * Appel de l'API de chargement d'une personne
   * @param {*} fieldValue 
   */
  function HttpFetchOne(fieldValue) {
    fieldValue = String(fieldValue).trim();
    let url = `${API_GETPERSONNE}?term=${fieldValue}`;
    fetch(url)
    .then(response => response.json())
    .then(datas => {
      libelleTarget.value = datas.libelle;
    });
  }

  /**
   * Appel de l'API de chargement de la liste des personnes 
   * (alimente la liste de la fenêtre modale)
   * @param {*} filtre 
   * @param {*} offset 
   * @param {*} count 
   */
  function HttpFetchAll(filtre="", offset=0, count=0) {
    if (count <= 0) {
      count = NB_LIGNES_MAX;
    }
    filtre = String(filtre).trim();
    let url = `${API_GETPERSONNES}?term=${filtre}&count=${count}&offset=${offset}`;
    fetch(url)
      .then(response => response.json())
      .then(datas => {
          // vidage préalable du contenu de la balise tbody
          dropChildren(tbodyTarget);
          datas.datas.forEach(data => {
              let newnode = document.createElement('tr');
              newnode.setAttribute('data-filter', `|${data['code']}|${String(data['libelle']).trim()}|` );
              let html = `<th scope="row"><button class="btn btn-primary btn-sm" data-click=id_${data['id']}
                  data-libelle="${data['libelle']}" >Sélect.</button></th>
              <th scope="row">${data['code']}</th>
              <td>${data['libelle']}</td>`;
              newnode.innerHTML = html;
              tbodyTarget.appendChild(newnode);
          })       
          pagenavGenerator(datas.count, offset, count); 
      });
    }

    // Déclaration des méthodes et propriétés publiques du module
    return {
      init: init,
  };
})(jQuery, Pagination);