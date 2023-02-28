import { HttpClient } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { Jugador } from '../interfaces/jugador.interface';

@Injectable({
  providedIn: 'root'
})
export class JugadorService {

  constructor(private http: HttpClient) { }

  get token(): string
  {
    return localStorage.getItem('x-token') || '';
  }

  obtenirDadesJugador(id: number){

    return this.http.get(`http://localhost:8000/api/onlyJugador/${id}`, {
      headers: {
        //aqui es pasan les dades del token ja que es necesiten per fer la consulta a la db.
        //inicialment s'agafaba del const token comentat adalt pero ara s'agafa del get token.
        "Content-Type":  "application/json",
        Accept: "application/json",
        Authorization: 'Bearer ' + this.token
      }//pipe es un metode d'observables que permet encadenar diferents operadors rxjs
    })
  }

  updateJugador(jugador: Jugador){

    return this.http.patch(`http://localhost:8000/api/actualitzarJugador/${jugador.id}`, jugador, {
      headers: {
        //aqui es pasan les dades del token ja que es necesiten per fer la consulta a la db.
        //inicialment s'agafaba del const token comentat adalt pero ara s'agafa del get token.
        "Content-Type":  "application/json",
        Accept: "application/json",
        Authorization: 'Bearer ' + this.token
      }//pipe es un metode d'observables que permet encadenar diferents operadors rxjs
    })
  }

  nouJugador(){

    const body = {}
    const headers =  {
      //aqui es pasan les dades del token ja que es necesiten per fer la consulta a la db.
      //inicialment s'agafaba del const token comentat adalt pero ara s'agafa del get token.
      "Content-Type":  "application/json",
      Accept: "application/json",
      Authorization: 'Bearer ' + this.token
    }

    return this.http.post('http://localhost:8000/api/nouJugador', body, {headers});

  }

  obtenirAllJugadorsClub(id: number, page: number){

    return this.http.get(`http://localhost:8000/api/clubJugadors/${id}?page=${page}`, {
      headers: {
        //aqui es pasan les dades del token ja que es necesiten per fer la consulta a la db.
        //inicialment s'agafaba del const token comentat adalt pero ara s'agafa del get token.
        "Content-Type":  "application/json",
        Accept: "application/json",
        Authorization: 'Bearer ' + this.token
      }//pipe es un metode d'observables que permet encadenar diferents operadors rxjs
    })
    
  }

  obtenirJugadorsDiferentsClub(id: number, page: number){

    return this.http.get(`http://localhost:8000/api/jugadorsDiferentClub/${id}?page=${page}`, {
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
