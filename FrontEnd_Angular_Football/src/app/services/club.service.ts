import { HttpClient } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { Usuari } from '../models/usuari.model';

@Injectable({
  providedIn: 'root'
})
export class ClubService {

  constructor(private http: HttpClient) { }

  get token(): string
  {
    return localStorage.getItem('x-token') || '';
  }

  onlyMyClub(id: number, page: number){

    return this.http.get(`http://localhost:8000/api/onlyClub/${id}?page=${page}`, {
      headers: {
        //aqui es pasan les dades del token ja que es necesiten per fer la consulta a la db.
        //inicialment s'agafaba del const token comentat adalt pero ara s'agafa del get token.
        "Content-Type":  "application/json",
        Accept: "application/json",
        Authorization: 'Bearer ' + this.token
      }
    });
  }

  allClubs(){

    return this.http.get(`http://localhost:8000/api/allClubs`, {
      headers: {
        //aqui es pasan les dades del token ja que es necesiten per fer la consulta a la db.
        //inicialment s'agafaba del const token comentat adalt pero ara s'agafa del get token.
        "Content-Type":  "application/json",
        Accept: "application/json",
        Authorization: 'Bearer ' + this.token
      }
    });
  }

}
