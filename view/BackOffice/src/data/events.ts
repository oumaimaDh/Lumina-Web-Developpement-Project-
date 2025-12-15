import { Event, Participant, Sponsor, AnalyticsData } from '../types/event';

export const events: Event[] = [
  {
    id: '1',
    title: 'Cultural Heritage Summit 2025',
    description: 'Annual summit exploring Tunisia\'s rich cultural heritage with international experts and local communities.',
    date: '2025-03-15',
    location: 'Tunis Convention Center',
    status: 'upcoming',
    deadline: '2025-03-10',
    participantCount: 156,
    sponsorCount: 8,
    assignedManager: 'Stella Walton',
    category: 'Culture'
  },
  {
    id: '2',
    title: 'Sahara Desert Expedition',
    description: 'A guided tour through the stunning landscapes of the Tunisian Sahara with professional guides.',
    date: '2025-04-20',
    location: 'Tozeur Desert Region',
    status: 'upcoming',
    deadline: '2025-04-15',
    participantCount: 45,
    sponsorCount: 5,
    assignedManager: 'Ahmed Ben Salem',
    category: 'Adventure'
  },
  {
    id: '3',
    title: 'Mediterranean Food Festival',
    description: 'Celebrate the diverse flavors of Mediterranean cuisine with renowned chefs and food enthusiasts.',
    date: '2025-02-28',
    location: 'Sousse Beachfront',
    status: 'in-progress',
    deadline: '2025-02-25',
    participantCount: 230,
    sponsorCount: 12,
    assignedManager: 'Stella Walton',
    category: 'Food & Beverage'
  },
  {
    id: '4',
    title: 'Ancient Ruins Workshop',
    description: 'Archaeological workshop at the historic ruins of Carthage for students and history enthusiasts.',
    date: '2025-05-10',
    location: 'Carthage Archaeological Site',
    status: 'upcoming',
    deadline: '2025-05-05',
    participantCount: 80,
    sponsorCount: 4,
    assignedManager: 'Leila Mansour',
    category: 'Education'
  },
  {
    id: '5',
    title: 'Medina Art Exhibition',
    description: 'Contemporary art exhibition showcasing talented local Tunisian artists and their unique perspectives.',
    date: '2025-01-20',
    location: 'Tunis Medina Cultural Center',
    status: 'completed',
    deadline: '2025-01-15',
    participantCount: 180,
    sponsorCount: 6,
    assignedManager: 'Youssef Trabelsi',
    category: 'Art'
  },
  {
    id: '6',
    title: 'Tech Innovation Conference 2025',
    description: 'Annual technology and innovation conference featuring startups and industry leaders.',
    date: '2025-06-12',
    location: 'Tunis Tech Hub',
    status: 'upcoming',
    deadline: '2025-06-05',
    participantCount: 320,
    sponsorCount: 15,
    assignedManager: 'Stella Walton',
    category: 'Technology'
  }
];

export const participants: Participant[] = [
  {
    id: '1',
    name: 'Sarah Martinez',
    email: 'sarah.m@example.com',
    phone: '+216 98 123 456',
    eventId: '1',
    eventName: 'Cultural Heritage Summit 2025',
    status: 'approved',
    joinDate: '2025-02-10',
    notes: 'VIP guest, requires special seating'
  },
  {
    id: '2',
    name: 'Ahmed Ben Ali',
    email: 'ahmed.b@example.com',
    phone: '+216 22 345 678',
    eventId: '3',
    eventName: 'Mediterranean Food Festival',
    status: 'approved',
    joinDate: '2025-02-08'
  },
  {
    id: '3',
    name: 'Marie Dubois',
    email: 'marie.d@example.com',
    phone: '+33 6 12 34 56 78',
    eventId: '1',
    eventName: 'Cultural Heritage Summit 2025',
    status: 'pending',
    joinDate: '2025-02-12'
  },
  {
    id: '4',
    name: 'John Smith',
    email: 'john.s@example.com',
    phone: '+1 555 123 4567',
    eventId: '6',
    eventName: 'Tech Innovation Conference 2025',
    status: 'approved',
    joinDate: '2025-02-05'
  },
  {
    id: '5',
    name: 'Fatima Zahra',
    email: 'fatima.z@example.com',
    phone: '+212 6 12 34 56 78',
    eventId: '2',
    eventName: 'Sahara Desert Expedition',
    status: 'pending',
    joinDate: '2025-02-13'
  },
  {
    id: '6',
    name: 'Marco Rossi',
    email: 'marco.r@example.com',
    phone: '+39 333 123 4567',
    eventId: '3',
    eventName: 'Mediterranean Food Festival',
    status: 'approved',
    joinDate: '2025-02-11'
  },
  {
    id: '7',
    name: 'Amina Khelifi',
    email: 'amina.k@example.com',
    phone: '+216 55 789 012',
    eventId: '4',
    eventName: 'Ancient Ruins Workshop',
    status: 'pending',
    joinDate: '2025-02-14'
  },
  {
    id: '8',
    name: 'David Chen',
    email: 'david.c@example.com',
    phone: '+86 138 0000 0000',
    eventId: '6',
    eventName: 'Tech Innovation Conference 2025',
    status: 'approved',
    joinDate: '2025-02-09'
  }
];

export const sponsors: Sponsor[] = [
  {
    id: '1',
    name: 'Tunisie Telecom',
    type: 'financial',
    contactEmail: 'partnership@tunisietelecom.tn',
    contactPhone: '+216 71 000 000',
    eventId: '6',
    eventName: 'Tech Innovation Conference 2025',
    contributionNotes: 'Gold sponsor - 50,000 TND contribution',
    contractStatus: 'Signed'
  },
  {
    id: '2',
    name: 'Mosaique FM',
    type: 'media',
    contactEmail: 'contact@mosaiquefm.net',
    contactPhone: '+216 71 111 111',
    eventId: '3',
    eventName: 'Mediterranean Food Festival',
    contributionNotes: 'Media coverage and promotional broadcasts',
    contractStatus: 'Signed'
  },
  {
    id: '3',
    name: 'Tunisia Airlines',
    type: 'financial',
    contactEmail: 'corporate@tunisair.com.tn',
    contactPhone: '+216 70 123 456',
    eventId: '1',
    eventName: 'Cultural Heritage Summit 2025',
    contributionNotes: 'Travel sponsor - discounted flights for international guests',
    contractStatus: 'Pending'
  },
  {
    id: '4',
    name: 'TechGear Solutions',
    type: 'equipment',
    contactEmail: 'sales@techgear.tn',
    contactPhone: '+216 98 222 333',
    eventId: '6',
    eventName: 'Tech Innovation Conference 2025',
    contributionNotes: 'Providing AV equipment and tech displays',
    contractStatus: 'Signed'
  },
  {
    id: '5',
    name: 'Express FM',
    type: 'media',
    contactEmail: 'redaction@expressfm.net',
    contactPhone: '+216 71 222 222',
    eventId: '1',
    eventName: 'Cultural Heritage Summit 2025',
    contributionNotes: 'Radio coverage and interviews',
    contractStatus: 'Signed'
  },
  {
    id: '6',
    name: 'Sahara Tours Co.',
    type: 'financial',
    contactEmail: 'info@saharatours.tn',
    contactPhone: '+216 76 333 444',
    eventId: '2',
    eventName: 'Sahara Desert Expedition',
    contributionNotes: 'Transportation and logistics support',
    contractStatus: 'Signed'
  },
  {
    id: '7',
    name: 'Olivia Olive Oil',
    type: 'other',
    contactEmail: 'marketing@olivia.tn',
    contactPhone: '+216 73 444 555',
    eventId: '3',
    eventName: 'Mediterranean Food Festival',
    contributionNotes: 'Product samples and tasting booth',
    contractStatus: 'Signed'
  },
  {
    id: '8',
    name: 'Heritage Foundation Tunisia',
    type: 'financial',
    contactEmail: 'grants@heritage.tn',
    contactPhone: '+216 71 555 666',
    eventId: '4',
    eventName: 'Ancient Ruins Workshop',
    contributionNotes: 'Educational grant - 20,000 TND',
    contractStatus: 'Signed'
  }
];

export const analyticsData: AnalyticsData = {
  totalEvents: 6,
  totalParticipants: 1011,
  totalSponsors: 50,
  mostPopularEvent: 'Tech Innovation Conference 2025',
  participationGrowth: 25,
  pendingRequests: 3
};
