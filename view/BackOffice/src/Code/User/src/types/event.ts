export interface Event {
  id: string;
  title: string;
  description: string;
  date: string;
  location: string;
  status: 'upcoming' | 'in-progress' | 'completed' | 'closed';
  deadline: string;
  participantCount: number;
  sponsorCount: number;
  assignedManager: string;
  category: string;
}

export interface Participant {
  id: string;
  name: string;
  email: string;
  phone: string;
  eventId: string;
  eventName: string;
  status: 'approved' | 'pending' | 'rejected';
  joinDate: string;
  notes?: string;
}

export interface Sponsor {
  id: string;
  name: string;
  type: 'financial' | 'media' | 'equipment' | 'other';
  contactEmail: string;
  contactPhone: string;
  eventId: string;
  eventName: string;
  contributionNotes: string;
  contractStatus?: string;
}

export interface AnalyticsData {
  totalEvents: number;
  totalParticipants: number;
  totalSponsors: number;
  mostPopularEvent: string;
  participationGrowth: number;
  pendingRequests: number;
}

export interface QuickStat {
  label: string;
  value: number | string;
  trend?: string;
  icon: string;
}
