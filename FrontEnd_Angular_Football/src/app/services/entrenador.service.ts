import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Entrenador } from '../interfaces/entrenador.interface';

@Injectable({
  providedIn: 'root'
})
export class EntrenadorService {

  constructor(private http: HttpClient) { }

  get token(): string
  {
    return localStorage.getItem('x-token') || '';
  }

  obtenirDadesManager(){

    return this.http.get(`http://localhost:8000/api/onlyEntrenador/`, {
      headers: {
        //aqui es pasan les dades del token ja que es necesiten per fer la consulta a la db.
        //inicialment s'agafaba del const token comentat adalt pero ara s'agafa del get token.
        "Content-Type":  "application/json",
        Accept: "application/json",
        Authorization: 'Bearer ' + this.token
      }//pipe es un metode d'observables que permet encadenar diferents operadors rxjs
    })
  }

  updateManager(entrenador: Entrenador){

    return this.http.patch(`http://localhost:8000/api/actualitzarEntrenador/${entrenador.id}`, entrenador, {
      headers: {
        //aqui es pasan les dades del token ja que es necesiten per fer la consulta a la db.
        //inicialment s'agafaba del const token comentat adalt pero ara s'agafa del get token.
        "Content-Type":  "application/json",
        Accept: "application/json",
        Authorization: 'Bearer ' + this.token
      }//pipe es un metode d'observables que permet encadenar diferents operadors rxjs
    })
  }

  obtenirAllEntrenadorClub(){

    return this.http.get(`http://localhost:8000/api/entrenadorsClub`, {
      headers: {
        //aqui es pasan les dades del token ja que es necesiten per fer la consulta a la db.
        //inicialment s'agafaba del const token comentat adalt pero ara s'agafa del get token.
        "Content-Type":  "application/json",
        Accept: "application/json",
        Authorization: 'Bearer ' + this.token
      }//pipe es un metode d'observables que permet encadenar diferents operadors rxjs
    })
    
  }

  obtenirAllEntrenadorDifferentClub(page: number){

    return this.http.get(`http://localhost:8000/api/entrenadorsDifferentClub?page=${page}`, {
      headers: {
        //aqui es pasan les dades del token ja que es necesiten per fer la consulta a la db.
        //inicialment s'agafaba del const token comentat adalt pero ara s'agafa del get token.
        "Content-Type":  "application/json",
        Accept: "application/json",
        Authorization: 'Bearer ' + this.token
      }//pipe es un metode d'observables que permet encadenar diferents operadors rxjs
    })
    
  }

}
