export interface Race {
  address?: string,  
  club_id: number,  
  end_date?: string,
  start_date?: string,
  status: string,
  title: string
}

export interface Rider {
  age: number,
  firstname: string,
  gender: string,
  grading?: string,
  surname?: string
}

export interface RaceResult {
  finish_position: number,
  finish_time: string,
  rider_id: number
}
