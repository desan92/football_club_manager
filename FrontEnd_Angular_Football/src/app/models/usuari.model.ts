export interface Club {
    id: number;
    nom_club: string;
    escut: string;
    email: string;
    telefon: string;
    ciutat: string;
    estadi: string;
    founded: number
}

export class Usuari{

    constructor(
        public id: number,
        public nom: string,
        public cognom: string,
        public email: string,
        public club_id: number,
        public club?: Club){
        
    }

    usuari_clubId(){
        return this.club_id;
    }
    
}