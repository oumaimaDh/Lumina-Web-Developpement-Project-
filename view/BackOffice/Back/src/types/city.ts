export interface City {
  id: string;
  name: string;
  nameAr: string;
  coordinates: { x: number; y: number };
  description: string;
  images: string[];
  highlights: string[];
}
